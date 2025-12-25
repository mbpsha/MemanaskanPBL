<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::active()
            ->withCount('registrations')
            ->orderBy('event_date', 'asc');

        // Search filter
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('location', 'like', "%{$request->search}%");
            });
        }

        // Status filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->has('date_from')) {
            $query->where('event_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('event_date', '<=', $request->date_to);
        }

        $events = $query->paginate(12)
            ->withQueryString()
            ->through(fn($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'slug' => $event->slug,
                'short_description' => $event->short_description,
                'event_date' => $event->event_date->format('Y-m-d H:i'),
                'formatted_date' => $event->event_date->isoFormat('LLL'),
                'location' => $event->location,
                'registration_fee' => $event->registration_fee,
                'status' => $event->status,
                'is_registration_open' => $event->isRegistrationOpen(),
                'available_slots' => $event->availableSlots(),
                'featured_image' => $event->featured_image ? asset('storage/' . $event->featured_image) : null,
                'registrations_count' => $event->registrations_count,
            ]);

        return Inertia::render('Events/Index', [
            'events' => $events,
            'filters' => $request->only(['search', 'status', 'date_from', 'date_to']),
        ]);
    }

    public function show(Event $event)
    {
        $event->loadCount('registrations');

        // Check if user is already registered
        $isRegistered = auth()->check()
            ? $event->registrations()
                ->where('user_id', auth()->id())
                ->exists()
            : false;

        return Inertia::render('Events/Show', [
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'slug' => $event->slug,
                'description' => $event->description,
                'short_description' => $event->short_description,
                'event_date' => $event->event_date->format('Y-m-d H:i'),
                'formatted_date' => $event->event_date->isoFormat('LLL'),
                'registration_open_date' => $event->registration_open_date->format('Y-m-d H:i'),
                'registration_close_date' => $event->registration_close_date->format('Y-m-d H:i'),
                'location' => $event->location,
                'registration_fee' => $event->registration_fee,
                'max_participants' => $event->max_participants,
                'current_participants' => $event->current_participants,
                'status' => $event->status,
                'categories' => $event->categories,
                'additional_fields' => $event->additional_fields,
                'featured_image' => $event->featured_image ? asset('storage/' . $event->featured_image) : null,
                'registrations_count' => $event->registrations_count,
                'is_registration_open' => $event->isRegistrationOpen(),
                'available_slots' => $event->availableSlots(),
            ],
            'isRegistered' => $isRegistered,
            'userRegistration' => $isRegistered ? auth()->user()->registrations()
                ->where('event_id', $event->id)
                ->first() : null,
        ]);
    }

    public function register(RegistrationRequest $request, Event $event)
    {
        // Check if registration is open
        if (!$event->isRegistrationOpen()) {
            return back()->withErrors(['event' => 'Registration is not open for this event.']);
        }

        // Check if user is already registered
        $existingRegistration = Registration::where('user_id', auth()->id())
            ->where('event_id', $event->id)
            ->first();

        if ($existingRegistration) {
            return back()->withErrors(['event' => 'You are already registered for this event.']);
        }

        // Create registration
        $registration = Registration::create([
            'user_id' => auth()->id(),
            'event_id' => $event->id,
            'category' => $request->category,
            'participant_info' => $request->participant_info,
            'registration_status' => 'draft',
            'payment_status' => 'pending',
        ]);

        // Increment participant count
        $event->increment('current_participants');

        return redirect()->route('dashboard')
            ->with('success', 'Registration successful! Please upload your payment proof to complete the registration.');
    }
}
