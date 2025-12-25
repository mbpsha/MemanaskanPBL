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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->json('short_description')->nullable();
            $table->dateTime('event_date');
            $table->dateTime('registration_open_date');
            $table->dateTime('registration_close_date');
            $table->string('location');
            $table->decimal('registration_fee', 10, 2);
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->enum('status', ['upcoming', 'registration_open', 'registration_closed', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->json('categories')->nullable();
            $table->json('additional_fields')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('featured_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
