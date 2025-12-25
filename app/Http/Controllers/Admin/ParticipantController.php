<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ParticipantsExport;

class ParticipantController extends Controller
{
    /**
     * Display a listing of participants.
     */
    public function index(Request $request)
    {
        $query = Registration::with(['user', 'event', 'paymentProof'])
            ->orderBy('created_at', 'desc');

        // Event filter
        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        // Registration status filter
        if ($request->has('registration_status')) {
            $query->where('registration_status', $request->registration_status);
        }

        // Payment status filter
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Category filter
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Search filter
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('registration_code', 'like', "%{$request->search}%")
                    ->orWhere('bib_number', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($uq) use ($request) {
                        $uq->where('name', 'like', "%{$request->search}%")
                            ->orWhere('email', 'like', "%{$request->search}%");
                    });
            });
        }

        $participants = $query->paginate(20)
            ->withQueryString()
            ->through(fn($registration) => [
                'id' => $registration->id,
                'registration_code' => $registration->registration_code,
                'bib_number' => $registration->bib_number,
                'user' => [
                    'id' => $registration->user->id,
                    'name' => $registration->user->name,
                    'email' => $registration->user->email,
                    'phone' => $registration->user->phone,
                ],
                'event' => [
                    'id' => $registration->event->id,
                    'name' => $registration->event->name,
                    'event_date' => $registration->event->event_date->format('Y-m-d'),
                ],
                'category' => $registration->category,
                'registration_status' => $registration->registration_status,
                'payment_status' => $registration->payment_status,
                'has_payment_proof' => $registration->paymentProof !== null,
                'payment_verified_at' => $registration->payment_verified_at?->format('Y-m-d H:i'),
                'created_at' => $registration->created_at->format('Y-m-d H:i'),
            ]);

        // Get events for filter dropdown
        $events = Event::select('id', 'name', 'event_date')
            ->orderBy('event_date', 'desc')
            ->get();

        return Inertia::render('Admin/Participants/Index', [
            'participants' => $participants,
            'events' => $events,
            'filters' => $request->only(['event_id', 'registration_status', 'payment_status', 'category', 'search']),
            'stats' => [
                'total' => Registration::count(),
                'approved' => Registration::where('registration_status', 'approved')->count(),
                'pending' => Registration::where('registration_status', 'pending')->count(),
                'payment_verified' => Registration::where('payment_status', 'verified')->count(),
            ],
        ]);
    }

    /**
     * Display the specified participant.
     */
    public function show(Registration $participant)
    {
        $participant->load(['user', 'event', 'paymentProof.verifier', 'verifier']);

        return Inertia::render('Admin/Participants/Show', [
            'participant' => [
                'id' => $participant->id,
                'registration_code' => $participant->registration_code,
                'bib_number' => $participant->bib_number,
                'category' => $participant->category,
                'participant_info' => $participant->participant_info,
                'registration_status' => $participant->registration_status,
                'payment_status' => $participant->payment_status,
                'payment_verified_at' => $participant->payment_verified_at?->format('Y-m-d H:i'),
                'admin_notes' => $participant->admin_notes,
                'metadata' => $participant->metadata,
                'user' => [
                    'id' => $participant->user->id,
                    'name' => $participant->user->name,
                    'email' => $participant->user->email,
                    'phone' => $participant->user->phone,
                    'birth_date' => $participant->user->birth_date?->format('Y-m-d'),
                    'address' => $participant->user->address,
                ],
                'event' => [
                    'id' => $participant->event->id,
                    'name' => $participant->event->name,
                    'slug' => $participant->event->slug,
                    'event_date' => $participant->event->event_date->format('Y-m-d H:i'),
                    'location' => $participant->event->location,
                    'registration_fee' => $participant->event->registration_fee,
                ],
                'payment_proof' => $participant->paymentProof ? [
                    'id' => $participant->paymentProof->id,
                    'amount' => $participant->paymentProof->amount,
                    'payment_method' => $participant->paymentProof->payment_method,
                    'payment_reference' => $participant->paymentProof->payment_reference,
                    'status' => $participant->paymentProof->status,
                    'proof_image' => $participant->paymentProof->proof_image ? asset('storage/' . $participant->paymentProof->proof_image) : null,
                    'verification_notes' => $participant->paymentProof->verification_notes,
                    'verified_at' => $participant->paymentProof->verified_at?->format('Y-m-d H:i'),
                ] : null,
                'verifier' => $participant->verifier ? [
                    'name' => $participant->verifier->name,
                    'email' => $participant->verifier->email,
                ] : null,
                'created_at' => $participant->created_at->format('Y-m-d H:i'),
            ],
        ]);
    }

    /**
     * Update participant BIB number.
     */
    public function updateBib(Request $request, Registration $participant)
    {
        $validated = $request->validate([
            'bib_number' => 'required|string|max:50|unique:registrations,bib_number,' . $participant->id,
        ]);

        $participant->update($validated);

        return back()->with('success', 'BIB number updated successfully!');
    }

    /**
     * Update participant notes.
     */
    public function updateNotes(Request $request, Registration $participant)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $participant->update($validated);

        return back()->with('success', 'Notes updated successfully!');
    }

    /**
     * Update participant status.
     */
    public function updateStatus(Request $request, Registration $participant)
    {
        $validated = $request->validate([
            'registration_status' => 'required|in:draft,pending,approved,rejected,cancelled',
        ]);

        $participant->update($validated);

        return back()->with('success', 'Status updated successfully!');
    }

    /**
     * Export participants to Excel.
     */
    public function export(Request $request)
    {
        $eventId = $request->get('event_id');
        $event = $eventId ? Event::find($eventId) : null;

        $filename = 'participants_' . ($event ? $event->slug . '_' : '') . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new ParticipantsExport($eventId), $filename);
    }

    /**
     * Bulk assign BIB numbers.
     */
    public function bulkAssignBib(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'start_number' => 'required|integer|min:1',
        ]);

        $registrations = Registration::where('event_id', $validated['event_id'])
            ->where('registration_status', 'approved')
            ->whereNull('bib_number')
            ->orderBy('created_at', 'asc')
            ->get();

        $bibNumber = $validated['start_number'];
        foreach ($registrations as $registration) {
            $registration->update(['bib_number' => str_pad($bibNumber, 4, '0', STR_PAD_LEFT)]);
            $bibNumber++;
        }

        return back()->with('success', count($registrations) . ' BIB numbers assigned successfully!');
    }
}
