<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Admin\RegistrationManagementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\RacepackController;
use App\Http\Controllers\Admin\ScannerController;
use App\Http\Controllers\EventRegistrationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Landing Page
Route::get('/', function () {
    return Inertia::render('Landing');
});

// Dashboard - redirect to landing page
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth'])->name('dashboard');


// Event Registration API
Route::prefix('api')->group(function () {
    // Register for event
    Route::post('/register', [RegistrationController::class, 'register']);

    // Upload payment proof
    Route::post('/register/{registrationCode}/payment', [RegistrationController::class, 'uploadPayment']);

    // Check registration status
    Route::get('/register/{registrationCode}/status', [RegistrationController::class, 'getStatus']);

    // Get all registrations (for testing)
    Route::get('/registrations', [RegistrationController::class, 'index']);
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Payment Verification
    Route::get('/payments', [PaymentVerificationController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/status', [PaymentVerificationController::class, 'updateStatus'])->name('payments.updateStatus');
    Route::get('/payments/{payment}/proof', [PaymentVerificationController::class, 'getPaymentProof'])->name('payments.proof');

    // Registration Management
    Route::get('/registrations', [RegistrationManagementController::class, 'index'])->name('registrations.index');
    Route::get('/registrations/{registration}', [RegistrationManagementController::class, 'show'])->name('registrations.show');

    // Payment Approval/Rejection
    Route::patch('/registrations/{registration}/approve', [RegistrationManagementController::class, 'approvePayment'])->name('registrations.approve');
    Route::patch('/registrations/{registration}/reject', [RegistrationManagementController::class, 'rejectPayment'])->name('registrations.reject');

    // Export
    Route::get('/registrations/export/csv', [RegistrationManagementController::class, 'export'])->name('registrations.export');

    // Racepack Collection
    Route::post('/racepack/scan', [RacepackController::class, 'updateStatus'])->name('racepack.scan');

    // Barcode Scanner
    Route::get('/scan', [ScannerController::class, 'index'])->name('scan.index');
    Route::post('/scan/verify', [ScannerController::class, 'verify'])->name('scan.verify');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/event-registrations', [EventRegistrationController::class, 'create'])->name('event.registrations');
    Route::post('/event-registrations', [EventRegistrationController::class, 'store'])->name('event.registrations.post');
});

require __DIR__ . '/auth.php';
