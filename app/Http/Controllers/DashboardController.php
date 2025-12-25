<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get user's registrations with related data
        $registrations = $user->registrations()
            ->with(['event', 'paymentProof'])
            ->latest()
            ->get()
            ->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'registration_code' => $registration->registration_code,
                    'category' => $registration->category,
                    'registration_status' => $registration->registration_status,
                    'payment_status' => $registration->payment_status,
                    'created_at' => $registration->created_at->format('Y-m-d H:i'),
                    'event' => [
                        'id' => $registration->event->id,
                        'name' => $registration->event->name,
                        'slug' => $registration->event->slug,
                        'event_date' => $registration->event->event_date->format('Y-m-d H:i'),
                        'location' => $registration->event->location,
                        'registration_fee' => $registration->event->registration_fee,
                    ],
                    'has_payment_proof' => $registration->paymentProof !== null,
                    'payment_proof_status' => $registration->paymentProof?->status,
                ];
            });

        return Inertia::render('Dashboard', [
            'registrations' => $registrations,
        ]);
    }
}
