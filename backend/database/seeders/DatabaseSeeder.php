<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin user (or update if exists)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'is_admin' => true,
                'password' => bcrypt('password'),
            ]
        );

        // Create a regular user (or update if exists)
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'is_admin' => false,
                'password' => bcrypt('password'),
            ]
        );

        // Create some additional test users only if they don't exist
        if (User::count() < 5) {
            User::factory(3)->create();
        }
    }
}
