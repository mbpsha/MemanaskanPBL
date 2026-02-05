<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->timestamp('racepack_collected_at')->nullable()->after('payment_verified_at');
            $table->foreignId('collected_by')->nullable()->after('racepack_collected_at')->constrained('users');
        });
    }

    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropForeign(['collected_by']);
            $table->dropColumn(['racepack_collected_at', 'collected_by']);
        });
    }
};
