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
        User::create([
            'name' => 'IT Administrator',
            'email' => 'itadmin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'IT',
            'is_active' => 1
        ]);

        User::create([
            'name' => 'Community User',
            'email' => 'community@test.com',
            'password' => bcrypt('password123'),
            'role' => 'Community',
            'is_active' => 1,
            'first_name' => 'Sample',
            'last_name' => 'Community',
            'phone' => '09123456789',
            'address' => 'Sample Address',
            'barangay' => 'Sample Barangay'
        ]);
    }
}
