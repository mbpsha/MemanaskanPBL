<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ScannerController extends Controller
{
    /**
     * Show the scan page
     */
    public function index()
    {
        return Inertia::render('Admin/Scan');
    }

    /**
     * Verify scanned registration code
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
        ]);

        $registration = EventRegistration::where('registration_code', $validated['code'])->first();

        if (!$registration) {
            return response()->json([
                'ok' => false,
                'message' => 'Kode registrasi tidak ditemukan.',
            ], 404);
        }

        // Check if payment is verified
        $isVerified = $registration->payment_status === 'verified';

        // Update payment status to "done" when scanned (only if currently verified)
        if ($registration->payment_status === 'verified') {
            $registration->payment_status = 'done';
            $registration->save();
        }

        return response()->json([
            'ok' => true,
            'verified' => $isVerified || $registration->payment_status === 'done', // Allow already collected participants
            'participant' => [
                'name' => $registration->name,
                'nik' => $registration->nik,
                'email' => $registration->email,
                'phone' => $registration->phone,
                'shirt_size' => $registration->shirt_size,
                'registration_code' => $registration->registration_code,
                'payment_status' => $registration->payment_status,
                'payment_verified_at' => $registration->payment_verified_at?->format('d F Y, H:i'),
            ],
        ]);
    }
}
