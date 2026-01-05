<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            // Modify payment_status enum to include 'done'
            $table->enum('payment_status', ['pending', 'uploaded', 'verified', 'rejected', 'done'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('payment_status', ['pending', 'uploaded', 'verified', 'rejected'])->default('pending')->change();
        });
    }
};
