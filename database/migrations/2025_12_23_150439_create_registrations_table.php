<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('registration_code')->unique();
            $table->string('category');
            $table->string('bib_number')->nullable();
            $table->json('participant_info')->nullable();
            $table->enum('registration_status', ['draft', 'pending', 'approved', 'rejected', 'cancelled'])->default('draft');
            $table->enum('payment_status', ['pending', 'uploaded', 'verified', 'rejected', 'expired'])->default('pending');
            $table->timestamp('payment_verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->text('admin_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'event_id']);
            $table->index(['registration_status', 'payment_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
