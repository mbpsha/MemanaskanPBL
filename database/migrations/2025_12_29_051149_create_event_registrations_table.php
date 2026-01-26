<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nik')->unique(); // National ID Number
            $table->text('address');
            $table->string('phone');
            $table->string('email');
            $table->text('illness')->nullable(); // Medical conditions
            $table->enum('shirt_size', ['XS', 'S', 'M', 'L', 'XL', 'XXL']);
            $table->string('payment_method')->nullable();

            // Payment proof
            $table->string('payment_proof_path')->nullable();
            $table->string('payment_proof_filename')->nullable();

            // Status tracking
            $table->enum('payment_status', ['pending', 'uploaded', 'verified', 'rejected', 'done'])->default('pending');
            $table->timestamp('payment_verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->text('rejection_reason')->nullable();

            // Registration code
            $table->string('registration_code')->unique();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
