<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Mail\PaymentApprovedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class RegistrationManagementController extends Controller
{
    /**
     * Display all registrations
     */
    public function index(Request $request)
    {
        $query = EventRegistration::query()->orderBy('created_at', 'desc');

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('registration_code', 'like', "%{$request->search}%")
                    ->orWhere('nik', 'like', "%{$request->search}%");
            });
        }

        $registrations = $query->paginate(20)->withQueryString();

        return Inertia::render('Admin/Registrations/Index', [
            'registrations' => $registrations->through(fn($reg) => [
                'id' => $reg->id,
                'registration_code' => $reg->registration_code,
                'name' => $reg->name,
                'nik' => $reg->nik,
                'email' => $reg->email,
                'phone' => $reg->phone,
                'shirt_size' => $reg->shirt_size,
                'payment_status' => $reg->payment_status,
                'payment_verified_at' => $reg->payment_verified_at?->format('Y-m-d H:i'),
                'has_payment_proof' => $reg->payment_proof_path !== null,
                'created_at' => $reg->created_at->format('Y-m-d H:i'),
            ]),
            'filters' => $request->only(['search', 'payment_status']),
            'stats' => [
                'total' => EventRegistration::count(),
                'pending' => EventRegistration::where('payment_status', 'pending')->count(),
                'uploaded' => EventRegistration::where('payment_status', 'uploaded')->count(),
                'verified' => EventRegistration::where('payment_status', 'verified')->count(),
                'rejected' => EventRegistration::where('payment_status', 'rejected')->count(),
            ],
        ]);
    }

    /**
     * Show registration details
     */
    public function show(EventRegistration $registration)
    {
        return Inertia::render('Admin/Registrations/Show', [
            'registration' => [
                'id' => $registration->id,
                'registration_code' => $registration->registration_code,
                'name' => $registration->name,
                'nik' => $registration->nik,
                'address' => $registration->address,
                'phone' => $registration->phone,
                'email' => $registration->email,
                'illness' => $registration->illness,
                'shirt_size' => $registration->shirt_size,
                'payment_method' => $registration->payment_method,
                'payment_status' => $registration->payment_status,
                'payment_proof' => $registration->payment_proof_path
                    ? asset('storage/' . $registration->payment_proof_path)
                    : null,
                'payment_verified_at' => $registration->payment_verified_at?->format('Y-m-d H:i'),
                'rejection_reason' => $registration->rejection_reason,
                'verifier' => $registration->verifier ? [
                    'name' => $registration->verifier->name,
                    'email' => $registration->verifier->email,
                ] : null,
                'created_at' => $registration->created_at->format('Y-m-d H:i'),
            ],
        ]);
    }

    /**
     * Approve payment
     */
    public function approvePayment(EventRegistration $registration)
    {
        if ($registration->payment_status !== 'uploaded') {
            return back()->withErrors(['error' => 'Payment proof must be uploaded first.']);
        }

        // Generate BIB number if not exists
        if (!$registration->bib_number) {
            $bib_number = $this->generateBibNumber($registration->gender);
            $registration->bib_number = $bib_number;
        }

        $registration->update([
            'payment_status' => 'verified',
            'payment_verified_at' => now(),
            'verified_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        // Send email notification with barcode
        try {
            Mail::to($registration->email)->send(new PaymentApprovedMail($registration));
        } catch (\Exception $e) {
            // Log error but don't fail the approval
            \Log::error('Failed to send payment approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'Payment approved, BIB number assigned, and email sent to participant!');
    }

    /**
     * Generate BIB number based on gender
     * Format: M5001 (Male) or F5001 (Female)
     * M/F = Gender, 5000 = 5K race, 001 = Sequential number
     */
    private function generateBibNumber($gender)
    {
        // Get the last BIB number for this gender
        $lastRegistration = EventRegistration::where('gender', $gender)
            ->whereNotNull('bib_number')
            ->orderBy('bib_number', 'desc')
            ->first();

        if ($lastRegistration && $lastRegistration->bib_number) {
            // Extract the number part (last 4 digits)
            $lastNumber = (int) substr($lastRegistration->bib_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            // Start from 5001 for first participant
            $nextNumber = 5001;
        }

        // Format: M5001 or F5001
        return $gender . $nextNumber;
    }

    /**
     * Reject payment
     */
    public function rejectPayment(Request $request, EventRegistration $registration)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $registration->update([
            'payment_status' => 'rejected',
            'payment_verified_at' => now(),
            'verified_by' => auth()->id(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return back()->with('success', 'Payment rejected.');
    }

    /**
     * Export registrations to Excel
     */
    public function export(Request $request)
    {
        $registrations = EventRegistration::orderBy('created_at', 'desc')->get();

        $filename = 'registrations_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'Registration Code',
                'Name',
                'NIK',
                'Email',
                'Phone',
                'Address',
                'Illness',
                'Shirt Size',
                'Payment Method',
                'Payment Status',
                'Verified At',
                'Registered At',
            ]);

            // Data
            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->registration_code,
                    $reg->name,
                    $reg->nik,
                    $reg->email,
                    $reg->phone,
                    $reg->address,
                    $reg->illness ?? 'None',
                    $reg->shirt_size,
                    $reg->payment_method ?? 'Not specified',
                    ucfirst($reg->payment_status),
                    $reg->payment_verified_at?->format('Y-m-d H:i') ?? 'Not verified',
                    $reg->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
