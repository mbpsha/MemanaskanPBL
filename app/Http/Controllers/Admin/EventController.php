<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Event::withCount('registrations')
            ->orderBy('created_at', 'desc');

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

        // Active filter
        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $events = $query->paginate(15)
            ->withQueryString()
            ->through(fn($event) => [
                'id' => $event->id,
                'name' => $event->name,
                'slug' => $event->slug,
                'event_date' => $event->event_date->format('Y-m-d H:i'),
                'formatted_date' => $event->event_date->isoFormat('LLL'),
                'location' => $event->location,
                'registration_fee' => $event->registration_fee,
                'status' => $event->status,
                'is_active' => $event->is_active,
                'max_participants' => $event->max_participants,
                'current_participants' => $event->current_participants,
                'registrations_count' => $event->registrations_count,
                'featured_image' => $event->featured_image ? asset('storage/' . $event->featured_image) : null,
            ]);

        return Inertia::render('Admin/Events/Index', [
            'events' => $events,
            'filters' => $request->only(['search', 'status', 'is_active']),
        ]);
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        return Inertia::render('Admin/Events/Create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|array',
            'event_date' => 'required|date|after:now',
            'registration_open_date' => 'required|date|before:event_date',
            'registration_close_date' => 'required|date|after:registration_open_date|before:event_date',
            'location' => 'required|string|max:255',
            'registration_fee' => 'required|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:upcoming,registration_open,registration_closed,ongoing,completed,cancelled',
            'categories' => 'required|array|min:1',
            'categories.*' => 'required|string',
            'additional_fields' => 'nullable|array',
            'is_active' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']) . '-' . now()->format('Y');

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('events', 'public');
        }

        $event = Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $event->loadCount('registrations');
        $event->load([
            'registrations' => function ($query) {
                $query->with('user')->latest()->limit(10);
            }
        ]);

        return Inertia::render('Admin/Events/Show', [
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
                'is_active' => $event->is_active,
                'featured_image' => $event->featured_image ? asset('storage/' . $event->featured_image) : null,
                'registrations_count' => $event->registrations_count,
                'recent_registrations' => $event->registrations->map(fn($reg) => [
                    'id' => $reg->id,
                    'registration_code' => $reg->registration_code,
                    'user_name' => $reg->user->name,
                    'category' => $reg->category,
                    'payment_status' => $reg->payment_status,
                    'created_at' => $reg->created_at->diffForHumans(),
                ]),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        return Inertia::render('Admin/Events/Edit', [
            'event' => [
                'id' => $event->id,
                'name' => $event->name,
                'slug' => $event->slug,
                'description' => $event->description,
                'short_description' => $event->short_description,
                'event_date' => $event->event_date->format('Y-m-d\TH:i'),
                'registration_open_date' => $event->registration_open_date->format('Y-m-d\TH:i'),
                'registration_close_date' => $event->registration_close_date->format('Y-m-d\TH:i'),
                'location' => $event->location,
                'registration_fee' => $event->registration_fee,
                'max_participants' => $event->max_participants,
                'status' => $event->status,
                'categories' => $event->categories,
                'additional_fields' => $event->additional_fields,
                'is_active' => $event->is_active,
                'featured_image' => $event->featured_image ? asset('storage/' . $event->featured_image) : null,
            ],
        ]);
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|array',
            'event_date' => 'required|date',
            'registration_open_date' => 'required|date|before:event_date',
            'registration_close_date' => 'required|date|after:registration_open_date|before:event_date',
            'location' => 'required|string|max:255',
            'registration_fee' => 'required|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'status' => 'required|in:upcoming,registration_open,registration_closed,ongoing,completed,cancelled',
            'categories' => 'required|array|min:1',
            'categories.*' => 'required|string',
            'additional_fields' => 'nullable|array',
            'is_active' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update slug if name changed
        if ($validated['name'] !== $event->name) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . now()->format('Y');
        }

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($event->featured_image) {
                Storage::disk('public')->delete($event->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Toggle event active status.
     */
    public function toggleActive(Event $event)
    {
        $event->update(['is_active' => !$event->is_active]);

        return back()->with('success', 'Event status updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Delete featured image
        if ($event->featured_image) {
            Storage::disk('public')->delete($event->featured_image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
