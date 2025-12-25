<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentProof;
use App\Models\Registration;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of payment proofs.
     */
    public function index(Request $request)
    {
        $query = PaymentProof::with(['registration.user', 'registration.event'])
            ->orderBy('created_at', 'desc');

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search filter
        if ($request->has('search')) {
            $query->whereHas('registration', function ($q) use ($request) {
                $q->where('registration_code', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($uq) use ($request) {
                        $uq->where('name', 'like', "%{$request->search}%")
                            ->orWhere('email', 'like', "%{$request->search}%");
                    });
            });
        }

        $payments = $query->paginate(20)
            ->withQueryString()
            ->through(fn($payment) => [
                'id' => $payment->id,
                'registration_code' => $payment->registration->registration_code,
                'user_name' => $payment->registration->user->name,
                'user_email' => $payment->registration->user->email,
                'event_name' => $payment->registration->event->name,
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'sender_account' => $payment->sender_account,
                'status' => $payment->status,
                'proof_image' => $payment->proof_path ? asset('storage/' . $payment->proof_path) : null,
                'verified_at' => $payment->verified_at?->format('Y-m-d H:i'),
                'created_at' => $payment->created_at->diffForHumans(),
            ]);

        return Inertia::render('Admin/Payments/Index', [
            'payments' => $payments,
            'filters' => $request->only(['search', 'status']),
            'stats' => [
                'pending' => PaymentProof::where('status', 'pending')->count(),
                'verified' => PaymentProof::where('status', 'verified')->count(),
                'rejected' => PaymentProof::where('status', 'rejected')->count(),
            ],
        ]);
    }

    /**
     * Display the specified payment proof.
     */
    public function show(PaymentProof $payment)
    {
        $payment->load(['registration.user', 'registration.event', 'verifier']);

        return Inertia::render('Admin/Payments/Show', [
            'payment' => [
                'id' => $payment->id,
                'registration' => [
                    'id' => $payment->registration->id,
                    'registration_code' => $payment->registration->registration_code,
                    'category' => $payment->registration->category,
                    'registration_status' => $payment->registration->registration_status,
                    'payment_status' => $payment->registration->payment_status,
                ],
                'user' => [
                    'id' => $payment->registration->user->id,
                    'name' => $payment->registration->user->name,
                    'email' => $payment->registration->user->email,
                    'phone' => $payment->registration->user->phone,
                ],
                'event' => [
                    'id' => $payment->registration->event->id,
                    'name' => $payment->registration->event->name,
                    'registration_fee' => $payment->registration->event->registration_fee,
                    'event_date' => $payment->registration->event->event_date->format('Y-m-d H:i'),
                ],
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'sender_account' => $payment->sender_account,
                'transfer_date' => $payment->transfer_date ? (string) $payment->transfer_date : null,
                'payment_notes' => $payment->notes,
                'status' => $payment->status,
                'proof_image' => $payment->proof_path ? asset('storage/' . $payment->proof_path) : null,
                'verified_at' => $payment->verified_at?->format('Y-m-d H:i'),
                'rejection_reason' => $payment->rejection_reason,
                'verifier' => $payment->verifier ? [
                    'name' => $payment->verifier->name,
                    'email' => $payment->verifier->email,
                ] : null,
                'created_at' => $payment->created_at->format('Y-m-d H:i'),
            ],
        ]);
    }

    /**
     * Approve payment proof.
     */
    public function approve(Request $request, PaymentProof $payment)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        // Update payment proof
        $payment->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Update registration
        $payment->registration->update([
            'payment_status' => 'verified',
            'payment_verified_at' => now(),
            'verified_by' => auth()->id(),
            'registration_status' => 'approved',
        ]);

        // TODO: Send email notification to user

        return back()->with('success', 'Payment approved successfully!');
    }

    /**
     * Reject payment proof.
     */
    public function reject(Request $request, PaymentProof $payment)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        // Update payment proof
        $payment->update([
            'status' => 'rejected',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        // Update registration
        $payment->registration->update([
            'payment_status' => 'rejected',
            'verified_by' => auth()->id(),
        ]);

        // TODO: Send email notification to user

        return back()->with('success', 'Payment rejected successfully!');
    }

    /**
     * Bulk approve payments.
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'payment_ids' => 'required|array',
            'payment_ids.*' => 'required|exists:payment_proofs,id',
        ]);

        $payments = PaymentProof::whereIn('id', $validated['payment_ids'])
            ->where('status', 'pending')
            ->get();

        foreach ($payments as $payment) {
            $payment->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);

            $payment->registration->update([
                'payment_status' => 'verified',
                'payment_verified_at' => now(),
                'verified_by' => auth()->id(),
                'registration_status' => 'approved',
            ]);
        }

        return back()->with('success', count($payments) . ' payments approved successfully!');
    }
}
