<?php

namespace App\Http\Controllers;

use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EventRegistrationController extends Controller
{
    /**
     * Show the registration form
     */
    public function create()
    {
        return Inertia::render('Event_Registrations');
    }

    /**
     * Store a new registration with payment proof
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'nik' => 'required|string|max:20|unique:event_registrations,nik',
                'address' => 'required|string',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|max:255',
                'gender' => 'required|in:M,F',
                'illness' => 'nullable|string',
                'shirt_size' => 'required|in:S,M,L,XL',
                'ticket_type' => 'required|string',
                'ticket_price' => 'required|integer',
                'transaction_id' => 'nullable|string',
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
                'agreement' => 'required|accepted',
            ]);

            // Handle payment proof upload
            $paymentProofPath = null;
            $paymentProofFilename = null;

            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                $paymentProofPath = $file->storeAs('payment-proofs', $filename, 'public');
                $paymentProofFilename = $file->getClientOriginalName();
            }

            // Create registration
            $registration = EventRegistration::create([
                'name' => $validated['name'],
                'nik' => $validated['nik'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'gender' => $validated['gender'],
                'illness' => $validated['illness'],
                'shirt_size' => $validated['shirt_size'],
                'payment_method' => $validated['ticket_type'], // Store ticket type as payment method
                'payment_proof_path' => $paymentProofPath,
                'payment_proof_filename' => $paymentProofFilename,
                'payment_status' => 'uploaded', // Already uploaded payment
            ]);

            // Return success with registration details
            return redirect('/')->with('success', 'Pendaftaran berhasil! Kode registrasi Anda: ' . $registration->registration_code . '. Pembayaran sedang diverifikasi oleh admin.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors
            return redirect('/')->with('error', 'Pendaftaran gagal! ' . collect($e->errors())->flatten()->first());

        } catch (\Exception $e) {
            // Other errors
            \Log::error('Registration failed: ' . $e->getMessage());
            return redirect('/')->with('error', 'Pendaftaran gagal! Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}