<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;


class EventRegistrationController extends Controller
{
    public function create()
    {
        return Inertia::render('Event_Registrations');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'nik'           => 'required|string|max:20',
            'address'       => 'required|string',
            'phone'         => 'required|string|max:20',
            'email'         => 'required|email',
            'illness'       => 'nullable|string',
            'shirt_size'    => 'required|in:M,L,XL',
            'payment_method'=> 'required|in:QRIS,qris,transfer,Transfer',
            'ticket_type'   => 'required|string',
            'ticket_price'  => 'required|integer',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Generate kode registrasi unik
        $registrationCode = 'RV2026-' . strtoupper(Str::random(8));

        // Upload payment proof
        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = $registrationCode . '_' . time() . '.' . $file->getClientOriginalExtension();
            $paymentProofPath = $file->storeAs('payment_proofs', $filename, 'public');
        }

        $registration = DB::table('event_registrations')->insertGetId([
            'name'              => $validated['name'],
            'nik'               => $validated['nik'],
            'address'           => $validated['address'],
            'phone'             => $validated['phone'],
            'email'             => $validated['email'],
            'illness'           => $validated['illness'],
            'shirt_size'        => $validated['shirt_size'],
            'payment_method'    => $validated['payment_method'],
            'payment_status'    => 'uploaded',
            'payment_proof_path'=> $paymentProofPath,
            'payment_proof_filename' => $filename ?? null,
            'registration_code' => $registrationCode,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Data yang dikirim balik ke frontend
        return redirect()->route('profile.edit')->with('success', 'Pendaftaran berhasil! Silakan tunggu verifikasi pembayaran.');
    }
}