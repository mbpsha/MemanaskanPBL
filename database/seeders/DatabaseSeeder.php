<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@webrunning.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);

        // Create regular test user
        User::create([
            'name' => 'Test User',
            'email' => 'user@webrunning.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '081234567891',
            'birth_date' => '1990-01-01',
            'address' => 'Jakarta, Indonesia',
            'email_verified_at' => now(),
        ]);

        // Create sample events
        Event::create([
            'name' => 'Jakarta Marathon 2025',
            'slug' => 'jakarta-marathon-2025',
            'description' => 'Join us for the biggest running event in Jakarta! Experience the thrill of running through the heart of the city with thousands of fellow runners.',
            'short_description' => ['Join the biggest running event in Jakarta'],
            'event_date' => now()->addMonths(2),
            'registration_open_date' => now(),
            'registration_close_date' => now()->addMonth(),
            'location' => 'Jakarta, Indonesia',
            'registration_fee' => 250000,
            'max_participants' => 5000,
            'current_participants' => 0,
            'status' => 'registration_open',
            'categories' => ['5K', '10K', '21K', '42K'],
            'additional_fields' => [],
            'is_active' => true,
        ]);

        Event::create([
            'name' => 'Bali Fun Run 2025',
            'slug' => 'bali-fun-run-2025',
            'description' => 'Run along the beautiful beaches of Bali in this fun and exciting event for all ages and fitness levels.',
            'short_description' => ['Fun run along Bali beaches'],
            'event_date' => now()->addMonths(3),
            'registration_open_date' => now(),
            'registration_close_date' => now()->addMonths(2),
            'location' => 'Bali, Indonesia',
            'registration_fee' => 150000,
            'max_participants' => 2000,
            'current_participants' => 0,
            'status' => 'registration_open',
            'categories' => ['5K', '10K'],
            'additional_fields' => [],
            'is_active' => true,
        ]);

        Event::create([
            'name' => 'Surabaya Night Run 2025',
            'slug' => 'surabaya-night-run-2025',
            'description' => 'Experience the city lights as you run through Surabaya at night. A unique running experience you won\'t forget!',
            'short_description' => ['Night running experience in Surabaya'],
            'event_date' => now()->addMonths(4),
            'registration_open_date' => now()->addWeek(),
            'registration_close_date' => now()->addMonths(3),
            'location' => 'Surabaya, Indonesia',
            'registration_fee' => 200000,
            'max_participants' => 3000,
            'current_participants' => 0,
            'status' => 'upcoming',
            'categories' => ['5K', '10K', '15K'],
            'additional_fields' => [],
            'is_active' => true,
        ]);
    }
}