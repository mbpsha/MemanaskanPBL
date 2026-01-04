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
            // Modify shirt_size enum to include 'S'
            $table->enum('shirt_size', ['S', 'M', 'L', 'XL'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            // Revert back to original enum values
            $table->enum('shirt_size', ['M', 'L', 'XL'])->change();
        });
    }
};
