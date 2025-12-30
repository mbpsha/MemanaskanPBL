<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Admin\RegistrationManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

// Landing Page
Route::get('/', function () {
    return Inertia::render('Landing');
});

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
    // Registration Management
    Route::get('/registrations', [RegistrationManagementController::class, 'index'])->name('registrations.index');
    Route::get('/registrations/{registration}', [RegistrationManagementController::class, 'show'])->name('registrations.show');

    // Payment Approval/Rejection
    Route::patch('/registrations/{registration}/approve', [RegistrationManagementController::class, 'approvePayment'])->name('registrations.approve');
    Route::patch('/registrations/{registration}/reject', [RegistrationManagementController::class, 'rejectPayment'])->name('registrations.reject');

    // Export
    Route::get('/registrations/export/csv', [RegistrationManagementController::class, 'export'])->name('registrations.export');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

