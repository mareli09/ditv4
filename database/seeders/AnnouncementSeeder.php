<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get CESO user
        $cesoUser = User::firstOrCreate(
            ['email' => 'ceso@test.com'],
            [
                'name' => 'CESO Staff',
                'password' => bcrypt('cesoPass123'),
                'role' => 'CESO',
                'is_active' => 1
            ]
        );

        // Published announcements
        Announcement::create([
            'title' => 'Enrollment Advisory',
            'content' => 'The enrollment period for the upcoming academic year is now open. All interested students are encouraged to complete their registration through the online portal. The deadline for submissions is March 31, 2026. For further assistance, please visit the registrar\'s office during office hours.',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'created_by' => $cesoUser->id,
            'updated_by' => $cesoUser->id,
        ]);

        Announcement::create([
            'title' => 'Scholarship Opportunities Available',
            'content' => 'We are pleased to announce the availability of various scholarship programs for the 2026-2027 academic term. Eligible students may apply for merit-based scholarships, need-based assistance, and special program grants. Application deadlines vary by program. Visit the Financial Aid Office for complete details and application materials.',
            'status' => 'published',
            'published_at' => now()->subDays(3),
            'created_by' => $cesoUser->id,
            'updated_by' => $cesoUser->id,
        ]);

        Announcement::create([
            'title' => 'Campus Maintenance Schedule',
            'content' => 'Please be advised that scheduled maintenance will occur on the following dates: Building A (March 1-5), Building B (March 8-12), Library (March 15-16). Some facilities may be temporarily unavailable during these periods. We apologize for any inconvenience and appreciate your patience.',
            'status' => 'published',
            'published_at' => now()->subDays(1),
            'created_by' => $cesoUser->id,
            'updated_by' => $cesoUser->id,
        ]);

        // Draft announcement
        Announcement::create([
            'title' => 'Call for Research Collaboration',
            'content' => 'We invite researchers and faculty members to submit proposals for collaborative research initiatives. This is an exciting opportunity to contribute to cutting-edge projects and advance our institution\'s research agenda. Interested parties should submit their proposals to the Research Office by April 15, 2026.',
            'status' => 'draft',
            'created_by' => $cesoUser->id,
            'updated_by' => $cesoUser->id,
        ]);

        Announcement::create([
            'title' => 'Mental Health and Wellness Resources',
            'content' => 'Your well-being matters. Our institution offers comprehensive mental health and wellness support services including counseling, peer support groups, and wellness workshops. All services are confidential and available to students, faculty, and staff. Contact the Wellness Center for more information.',
            'status' => 'published',
            'published_at' => now()->subDays(7),
            'created_by' => $cesoUser->id,
            'updated_by' => $cesoUser->id,
        ]);

        Announcement::create([
            'title' => 'New Library Study Spaces',
            'content' => 'The library is pleased to announce the opening of new collaborative study spaces and quiet zones. These modern facilities include computer workstations, charging stations, and comfortable seating arrangements. Extended library hours are now in effect during finals week.',
            'status' => 'published',
            'published_at' => now()->subDays(10),
            'created_by' => $cesoUser->id,
            'updated_by' => $cesoUser->id,
        ]);
    }
}
