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
            'uploaded' => 'pending',
            'verified' => 'verified',
            'rejected' => 'rejected'
        ];

        $payment->update([
            'payment_status' => $statusMap[$validated['status']],
            'verified_by' => Auth::id(),
            'payment_verified_at' => now(),
            'rejection_reason' => $validated['rejection_reason'] ?? null
        ]);

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
