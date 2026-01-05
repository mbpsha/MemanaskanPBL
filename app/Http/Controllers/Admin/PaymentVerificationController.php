<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PaymentVerificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');
        $sort = $request->input('sort', 'desc'); // default newest first

        $payments = EventRegistration::query()
            ->whereIn('payment_status', ['uploaded', 'verified', 'rejected'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('registration_code', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter, function ($query, $statusFilter) {
                if ($statusFilter === 'pending') {
                    $query->where('payment_status', 'uploaded');
                } else {
                    $query->where('payment_status', $statusFilter);
                }
            })
            ->when($sort === 'asc', function ($query) {
                $query->orderBy('name', 'asc');
            })
            ->when($sort === 'desc', function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('AdminPaymentVerification', [
            'payments' => $payments,
            'filters' => [
                'search' => $search,
                'status' => $statusFilter,
                'sort' => $sort
            ]
        ]);
    }

    public function updateStatus(Request $request, EventRegistration $payment)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:uploaded,verified,rejected'],
            'rejection_reason' => ['required_if:status,rejected', 'nullable', 'string']
        ]);

        $statusMap = [
            'uploaded' => 'uploaded',
            'verified' => 'verified',
            'rejected' => 'rejected'
        ];


        $newStatus = $statusMap[$validated['status']];

        // Store old status before updating
        $oldStatus = $payment->payment_status;

        // Update payment status first
        $payment->update([
            'payment_status' => $newStatus,
            'verified_by' => Auth::id(),
            'payment_verified_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null
        ]);

        // If verifying payment for the FIRST time, generate BIB number and send email
        if ($newStatus === 'verified' && $oldStatus !== 'verified') {
            // Generate BIB number if not exists
            if (!$payment->bib_number) {
                $gender = $payment->gender ?? 'M'; // Default to M if not set
                $baseNumber = 5000;

                // Get the last BIB number for this gender
                $lastBib = EventRegistration::where('gender', $gender)
                    ->whereNotNull('bib_number')
                    ->orderBy('bib_number', 'desc')
                    ->first();

                if ($lastBib && preg_match('/^[MF](\d+)$/', $lastBib->bib_number, $matches)) {
                    $lastNumber = (int) $matches[1];
                    $nextNumber = $lastNumber + 1;
                } else {
                    $nextNumber = $baseNumber + 1;
                }

                $payment->bib_number = $gender . $nextNumber;
                $payment->save(); // Save BIB number
            }

            // Refresh model to get updated data
            $payment->refresh();

            // Send approval email
            try {
                \Mail::to($payment->email)->send(new \App\Mail\PaymentApprovedMail($payment));
                \Log::info('Payment approval email sent to: ' . $payment->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send payment approval email: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Status pembayaran berhasil diupdate');
    }

    public function getPaymentProof(EventRegistration $payment)
    {
        if (!$payment->payment_proof_path) {
            abort(404, 'Bukti pembayaran tidak ditemukan');
        }

        $path = storage_path('app/public/' . $payment->payment_proof_path);

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($path);
    }
}
