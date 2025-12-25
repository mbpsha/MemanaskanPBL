<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Using Laravel 12's query builder improvements
        $stats = [
            'total_participants' => Registration::count(),
            'pending_payments' => Registration::where('payment_status', 'pending')->count(),
            'verified_payments' => Registration::where('payment_status', 'verified')->count(),
            'total_events' => Event::count(),
            'active_events' => Event::active()->count(),
            'total_users' => User::count(),
        ];

        // Recent registrations using Laravel 12's latest() method
        $recentRegistrations = Registration::with(['user', 'event'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'registration_code' => $registration->registration_code,
                    'user_name' => $registration->user->name,
                    'event_name' => $registration->event->name,
                    'payment_status' => $registration->payment_status,
                    'registration_status' => $registration->registration_status,
                    'created_at' => $registration->created_at->diffForHumans(),
                ];
            });

        // Events needing attention
        $eventsNeedingAttention = Event::where(function ($query) {
            $query->where('current_participants', '>=', DB::raw('max_participants * 0.9'))
                ->orWhere('status', 'registration_closed')
                ->orWhere('event_date', '<', now()->addDays(7));
        })
            ->where('is_active', true)
            ->limit(5)
            ->get();

        // Payment status distribution
        $paymentStatusDistribution = Registration::select('payment_status', DB::raw('count(*) as count'))
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status');

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentRegistrations' => $recentRegistrations,
            'eventsNeedingAttention' => $eventsNeedingAttention,
            'paymentStatusDistribution' => $paymentStatusDistribution,
        ]);
    }
}
