<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    /**
     * Register for the event (5K only)
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:event_registrations,nik',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'illness' => 'nullable|string',
            'shirt_size' => 'required|in:M,L,XL',
            'payment_method' => 'nullable|string|max:100',
        ]);

        // Create registration
        $registration = EventRegistration::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful! Please upload your payment proof.',
            'data' => [
                'registration_code' => $registration->registration_code,
                'id' => $registration->id,
            ]
        ], 201);
    }

    /**
     * Upload payment proof
     */
    public function uploadPayment(Request $request, $registrationCode)
    {
        $registration = EventRegistration::where('registration_code', $registrationCode)->firstOrFail();

        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('payment_proof')) {
            // Delete old proof if exists
            if ($registration->payment_proof_path) {
                Storage::disk('public')->delete($registration->payment_proof_path);
            }

            $file = $request->file('payment_proof');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('payment-proofs', $filename, 'public');

            $registration->update([
                'payment_proof_path' => $path,
                'payment_proof_filename' => $file->getClientOriginalName(),
                'payment_status' => 'uploaded',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment proof uploaded successfully. Waiting for admin verification.',
            'data' => [
                'registration_code' => $registration->registration_code,
                'payment_status' => $registration->payment_status,
            ]
        ]);
    }

    /**
     * Get registration status
     */
    public function getStatus($registrationCode)
    {
        $registration = EventRegistration::where('registration_code', $registrationCode)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'registration_code' => $registration->registration_code,
                'name' => $registration->name,
                'email' => $registration->email,
                'shirt_size' => $registration->shirt_size,
                'payment_status' => $registration->payment_status,
                'payment_verified_at' => $registration->payment_verified_at?->format('Y-m-d H:i:s'),
                'rejection_reason' => $registration->rejection_reason,
                'has_payment_proof' => $registration->payment_proof_path !== null,
            ]
        ]);
    }

    /**
     * Get all registrations (for testing/admin preview)
     */
    public function index()
    {
        $registrations = EventRegistration::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $registrations->map(fn($reg) => [
                'id' => $reg->id,
                'registration_code' => $reg->registration_code,
                'name' => $reg->name,
                'email' => $reg->email,
                'phone' => $reg->phone,
                'shirt_size' => $reg->shirt_size,
                'payment_status' => $reg->payment_status,
                'created_at' => $reg->created_at->format('Y-m-d H:i:s'),
            ])
        ]);
    }
}
