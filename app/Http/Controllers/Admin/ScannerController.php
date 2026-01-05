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
     * Public verification endpoint for QR code scanning (no auth required)
     */
    public function publicVerify($code)
    {
        $registration = EventRegistration::where('registration_code', $code)->first();

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Kode registrasi tidak ditemukan.',
            ], 404);
        }

        // Check if payment is verified
        $isVerified = $registration->payment_status === 'verified' || $registration->payment_status === 'done';

        if (!$isVerified) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran belum diverifikasi.',
                'payment_status' => $registration->payment_status,
            ], 403);
        }

        // Update payment status to "done" when scanned (only if currently verified)
        if ($registration->payment_status === 'verified') {
            $registration->payment_status = 'done';
            $registration->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Tiket berhasil diverifikasi!',
            'participant' => [
                'name' => $registration->name,
                'bib_number' => $registration->bib_number,
                'shirt_size' => $registration->shirt_size,
                'registration_code' => $registration->registration_code,
                'payment_status' => $registration->payment_status,
            ],
        ]);
    }

    /**
     * Verify scanned registration code (for admin panel)
     */
    public function verify(Request $request)
    {
        $code = $request->query('code');

        if (!$code) {
            return response()->json([
                'ok' => false,
                'message' => 'Kode registrasi tidak ditemukan.',
            ], 400);
        }

        $registration = EventRegistration::where('registration_code', $code)->first();

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
