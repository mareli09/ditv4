<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 projects
        $projects = [
            [
                'title' => 'Community Health Awareness Program',
                'description' => 'A comprehensive health awareness initiative targeting the community with focus on preventive care and wellness.',
                'conducted_by' => 'Health and Wellness Department',
                'target_audience' => 'Community, Faculty, Staff',
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(6),
                'status' => 'Proposed',
            ],
            [
                'title' => 'Environmental Sustainability Initiative',
                'description' => 'Project focused on promoting sustainable practices within the institution and the surrounding community.',
                'conducted_by' => 'Environmental Science Department',
                'target_audience' => 'Students, Faculty, Community',
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(9),
                'status' => 'Proposed',
            ],
            [
                'title' => 'Youth Skills Development Program',
                'description' => 'Training and mentorship program to develop professional and technical skills in youth from the community.',
                'conducted_by' => 'Human Development Center',
                'target_audience' => 'Students, Community Youth',
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(12),
                'status' => 'Ongoing',
            ],
            [
                'title' => 'Digital Literacy for All',
                'description' => 'Initiative to bridge the digital divide by providing digital literacy training to underserved communities.',
                'conducted_by' => 'Computer Science Department',
                'target_audience' => 'Community, Senior Citizens',
                'start_date' => now(),
                'end_date' => now()->addMonths(8),
                'status' => 'Ongoing',
            ],
            [
                'title' => 'Educational Scholarship Program',
                'description' => 'Scholarship and financial aid program for deserving students from underprivileged backgrounds.',
                'conducted_by' => 'Student Affairs Office',
                'target_audience' => 'Students, Community',
                'start_date' => now()->subMonths(3),
                'end_date' => now()->addMonths(9),
                'status' => 'Ongoing',
            ],
            [
                'title' => 'Community Art and Culture Festival',
                'description' => 'Annual festival celebrating local arts, culture, and heritage with community participation and engagement.',
                'conducted_by' => 'Arts and Culture Committee',
                'target_audience' => 'Community, Students, Faculty',
                'start_date' => now()->addMonths(3),
                'end_date' => now()->addMonths(4),
                'status' => 'Proposed',
            ],
            [
                'title' => 'Disaster Risk Reduction Program',
                'description' => 'Project aimed at building community resilience and preparedness for disaster management and emergency response.',
                'conducted_by' => 'Safety and Security Office',
                'target_audience' => 'Community, Faculty, Staff',
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(10),
                'status' => 'Proposed',
            ],
            [
                'title' => 'Clean Water and Sanitation Drive',
                'description' => 'Initiative to improve access to clean water and promote proper sanitation practices in rural communities.',
                'conducted_by' => 'Public Health Department',
                'target_audience' => 'Community',
                'start_date' => now()->addMonths(2),
                'end_date' => now()->addMonths(7),
                'status' => 'Proposed',
            ],
            [
                'title' => 'Entrepreneurship and Small Business Support',
                'description' => 'Mentorship and funding support program for aspiring entrepreneurs and small business owners in the community.',
                'conducted_by' => 'Business Development Center',
                'target_audience' => 'Community, Graduates',
                'start_date' => now()->addMonths(1),
                'end_date' => now()->addMonths(12),
                'status' => 'Ongoing',
            ],
            [
                'title' => 'Mental Health and Wellness Campaign',
                'description' => 'Campaign to raise awareness about mental health, reduce stigma, and provide support resources to the community.',
                'conducted_by' => 'Psychology Department',
                'target_audience' => 'Students, Faculty, Community',
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => 'Ongoing',
            ],
        ];

        // Get all users for assignment
        $allUsers = User::all();

        if ($allUsers->isEmpty()) {
            // If no users exist, create some
            $allUsers = collect();
            for ($i = 1; $i <= 10; $i++) {
                $user = User::create([
                    'name' => "Project Member {$i}",
                    'email' => "projectmember{$i}@test.com",
                    'password' => bcrypt('password123'),
                    'role' => 'Community',
                    'is_active' => 1,
                    'first_name' => "Member",
                    'last_name' => "User {$i}",
                ]);
                $allUsers->push($user);
            }
        }

        // Create projects with assigned users
        foreach ($projects as $projectData) {
            $project = Project::create($projectData);

            // Assign up to 10 random users to each project
            $usersForProject = $allUsers->random(min(10, $allUsers->count()));
            
            foreach ($usersForProject as $user) {
                $project->users()->attach($user->id, [
                    'role' => ['member', 'coordinator', 'supervisor'][rand(0, 2)]
                ]);
            }
        }
    }
}
