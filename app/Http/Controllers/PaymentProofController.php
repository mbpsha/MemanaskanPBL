<?php

namespace App\Http\Controllers;

use App\Models\PaymentProof;
use App\Models\Registration;
use App\Http\Requests\PaymentProofRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PaymentProofController extends Controller
{
    public function store(PaymentProofRequest $request, Registration $registration)
    {
        // Check if registration belongs to user
        if ($registration->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if payment proof already exists
        if ($registration->paymentProof) {
            // Delete old file
            Storage::disk('public')->delete($registration->paymentProof->proof_path);
            $registration->paymentProof->delete();
        }

        // Handle file upload with Laravel 12's improved file handling
        $file = $request->file('proof');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('payment-proofs', $filename, 'public');

        // Create payment proof record
        $paymentProof = PaymentProof::create([
            'registration_id' => $registration->id,
            'proof_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'payment_method' => $request->payment_method,
            'sender_account' => $request->sender_account,
            'amount' => $request->amount,
            'transfer_date' => $request->transfer_date,
            'notes' => $request->notes,
        ]);

        // Update registration status
        $registration->update([
            'payment_status' => 'uploaded',
            'registration_status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Payment proof uploaded successfully. Please wait for verification.');
    }

    public function show(PaymentProof $paymentProof)
    {
        // Authorization check
        $user = auth()->user();

        // Allow if user owns the registration or is admin
        if ($paymentProof->registration->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $filePath = Storage::disk('public')->path($paymentProof->proof_path);

        // Return file with appropriate headers
        return response()->file($filePath, [
            'Content-Type' => mime_content_type($filePath),
            'Content-Disposition' => 'inline; filename="' . $paymentProof->original_filename . '"',
        ]);
    }
}
