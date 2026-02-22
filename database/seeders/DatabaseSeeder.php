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

        // Fetch registered users
        $users = \App\Models\User::take(15)->get();

        // Add dummy feedback for testing
        $activity = \App\Models\Activity::first(); // Use the first activity for testing

        if ($activity && $users->isNotEmpty()) {
            $feedbackData = [
                ['comment' => 'The activity was very informative and engaging.', 'rating' => 5, 'role' => 'Student'],
                ['comment' => 'Well-organized but could use more interactive sessions.', 'rating' => 4, 'role' => 'Faculty'],
                ['comment' => 'Great initiative for the community!', 'rating' => 5, 'role' => 'Community'],
                ['comment' => 'It was okay, but I expected more.', 'rating' => 3, 'role' => 'Student'],
                ['comment' => 'Not very engaging.', 'rating' => 2, 'role' => 'Faculty'],
                ['comment' => 'Excellent event! Very well planned.', 'rating' => 5, 'role' => 'Community'],
                ['comment' => 'Could have been better.', 'rating' => 3, 'role' => 'Student'],
                ['comment' => 'Loved the interactive sessions.', 'rating' => 4, 'role' => 'Faculty'],
                ['comment' => 'A bit too long, but informative.', 'rating' => 3, 'role' => 'Community'],
                ['comment' => 'Fantastic experience!', 'rating' => 5, 'role' => 'Student'],
                ['comment' => 'The venue was not great.', 'rating' => 2, 'role' => 'Faculty'],
                ['comment' => 'Very inspiring!', 'rating' => 5, 'role' => 'Community'],
                ['comment' => 'Average event.', 'rating' => 3, 'role' => 'Student'],
                ['comment' => 'Good effort, but needs improvement.', 'rating' => 3, 'role' => 'Faculty'],
                ['comment' => 'Highly recommended!', 'rating' => 5, 'role' => 'Community'],
            ];

            foreach ($feedbackData as $index => $feedback) {
                $activity->feedback()->create([
                    'user_id' => $users[$index % $users->count()]->id, // Rotate through users
                    'role' => $feedback['role'],
                    'comment' => $feedback['comment'],
                    'rating' => $feedback['rating'],
                ]);
            }
        }
    }
}
