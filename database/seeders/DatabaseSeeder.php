<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'itadmin@test.com'],
            [
                'name' => 'IT Administrator',
                'password' => bcrypt('password123'),
                'role' => 'IT',
                'is_active' => 1,
            ]
        );

        User::updateOrCreate(
            ['email' => 'community@test.com'],
            [
                'name' => 'Community User',
                'password' => bcrypt('password123'),
                'role' => 'Community',
                'is_active' => 1,
                'first_name' => 'Sample',
                'last_name' => 'Community',
                'phone' => '09123456789',
                'address' => 'Sample Address',
                'barangay' => 'Sample Barangay'
            ]
        );

        User::updateOrCreate(
            ['email' => 'ceso@test.com'],
            [
                'name' => 'CESO Staff',
                'password' => bcrypt('cesoPass123'),
                'role' => 'CESO',
                'is_active' => 1
            ]
        );
    }
}
