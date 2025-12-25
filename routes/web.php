<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('events.index');
});

use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public event routes
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentProofController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

// Authenticated user routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Event registration
    Route::post('/events/{event}/register', [EventController::class, 'register'])->name('events.register');

    // Payment proof
    Route::post('/registrations/{registration}/payment-proof', [PaymentProofController::class, 'store'])->name('payment-proof.store');
    Route::get('/payment-proofs/{paymentProof}', [PaymentProofController::class, 'show'])->name('payment-proof.show');
});

// Admin routes
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ParticipantController as AdminParticipantController;

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Event Management
    Route::resource('events', AdminEventController::class);
    Route::patch('/events/{event}/toggle-active', [AdminEventController::class, 'toggleActive'])->name('events.toggle-active');

    // Payment Verification
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
    Route::patch('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
    Route::patch('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    Route::post('/payments/bulk-approve', [AdminPaymentController::class, 'bulkApprove'])->name('payments.bulk-approve');

    // Participant Management
    Route::get('/participants', [AdminParticipantController::class, 'index'])->name('participants.index');
    Route::get('/participants/{participant}', [AdminParticipantController::class, 'show'])->name('participants.show');
    Route::patch('/participants/{participant}/bib', [AdminParticipantController::class, 'updateBib'])->name('participants.update-bib');
    Route::patch('/participants/{participant}/notes', [AdminParticipantController::class, 'updateNotes'])->name('participants.update-notes');
    Route::patch('/participants/{participant}/status', [AdminParticipantController::class, 'updateStatus'])->name('participants.update-status');
    Route::get('/participants/export/excel', [AdminParticipantController::class, 'export'])->name('participants.export');
    Route::post('/participants/bulk-assign-bib', [AdminParticipantController::class, 'bulkAssignBib'])->name('participants.bulk-assign-bib');
});

require __DIR__ . '/auth.php';
