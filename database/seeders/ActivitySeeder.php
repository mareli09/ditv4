<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityFeedback;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        // POSITIVE ACTIVITY
        $positiveActivity = Activity::create([
            'title' => 'Advanced Research Methods Workshop - Positive',
            'venue' => 'Conference Hall A, Building 3',
            'start_date' => now()->subDays(10),
            'end_date' => now()->subDays(10),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'conducted_by' => 'Research Office',
            'fee' => 500,
            'description' => 'An intensive workshop on advanced research methodologies and data analysis techniques. This session covers qualitative and quantitative approaches, statistical analysis, and practical applications in various fields.',
            'entry_code' => Activity::generateEntryCode(),
            'requires_entry_code' => true,
        ]);

        // Positive feedback
        $positiveFeedback = [
            ['role' => 'Faculty', 'rating' => 5, 'comment' => 'Excellent comprehensive workshop! The instructors were knowledgeable and engaging throughout the entire session. Highly recommend for all researchers.'],
            ['role' => 'Faculty', 'rating' => 5, 'comment' => 'Outstanding content and presentation. The materials provided are comprehensive and useful for future research projects.'],
            ['role' => 'Student', 'rating' => 5, 'comment' => 'This was incredibly helpful for my thesis research. The practical examples and case studies made everything clear and applicable.'],
            ['role' => 'Student', 'rating' => 4, 'comment' => 'Great workshop! Very informative and the instructors answered all questions patiently. Best professional development I have attended.'],
            ['role' => 'Staff', 'rating' => 5, 'comment' => 'Well-organized and perfectly timed. The logistics were flawless and the content was highly relevant to our work.'],
            ['role' => 'Community', 'rating' => 4, 'comment' => 'Very enlightening experience. I learned practical research techniques that I can apply to community projects immediately.'],
        ];

        foreach ($positiveFeedback as $feedback) {
            ActivityFeedback::create([
                'activity_id' => $positiveActivity->id,
                'role' => $feedback['role'],
                'rating' => $feedback['rating'],
                'comment' => $feedback['comment'],
                'source' => 'feedback_form',
            ]);
        }

        // NEGATIVE ACTIVITY
        $negativeActivity = Activity::create([
            'title' => 'Leadership Development Seminar - Negative',
            'venue' => 'Small Room 102, Building 1',
            'start_date' => now()->subDays(8),
            'end_date' => now()->subDays(8),
            'start_time' => '14:00',
            'end_time' => '18:00',
            'conducted_by' => 'Human Resources',
            'fee' => 800,
            'description' => 'A seminar on developing leadership skills and team management techniques designed for middle-level managers and supervisors.',
            'entry_code' => Activity::generateEntryCode(),
            'requires_entry_code' => true,
        ]);

        // Negative feedback
        $negativeFeedback = [
            ['role' => 'Faculty', 'rating' => 2, 'comment' => 'Disappointing seminar. The content was too generic and not tailored for academic leadership. Felt like a waste of time and money.'],
            ['role' => 'Faculty', 'rating' => 2, 'comment' => 'Poor organization and inadequate preparation. Speakers seemed unprepared and the materials were outdated. Not worth the fee.'],
            ['role' => 'Student', 'rating' => 1, 'comment' => 'Boring and irrelevant. The examples used were outdated and the delivery was flat. Could not stay awake through it.'],
            ['role' => 'Staff', 'rating' => 2, 'comment' => 'Terrible logistics. We had to sit in an uncomfortable room with poor ventilation. The content was also unclear and disconnected.'],
            ['role' => 'Staff', 'rating' => 2, 'comment' => 'Misleading title. It was more about corporate management than actual leadership development. Complete disappointment.'],
            ['role' => 'Community', 'rating' => 1, 'comment' => 'Worst event I have attended. Expensive, poorly organized, and the facilitators were condescending to participants.'],
        ];

        foreach ($negativeFeedback as $feedback) {
            ActivityFeedback::create([
                'activity_id' => $negativeActivity->id,
                'role' => $feedback['role'],
                'rating' => $feedback['rating'],
                'comment' => $feedback['comment'],
                'source' => 'feedback_form',
            ]);
        }

        // NEUTRAL ACTIVITY
        $neutralActivity = Activity::create([
            'title' => 'Community Outreach Program - Neutral',
            'venue' => 'Community Center, Downtown',
            'start_date' => now()->subDays(5),
            'end_date' => now()->subDays(5),
            'start_time' => '10:00',
            'end_time' => '16:00',
            'conducted_by' => 'Community Services',
            'fee' => 300,
            'description' => 'An outreach program aiming to connect the university with the local community through various activities and information sessions.',
            'entry_code' => Activity::generateEntryCode(),
            'requires_entry_code' => true,
        ]);

        // Neutral feedback
        $neutralFeedback = [
            ['role' => 'Faculty', 'rating' => 3, 'comment' => 'It was okay. Some good points were raised but overall the program could have been better structured. Worth attending but nothing exceptional.'],
            ['role' => 'Faculty', 'rating' => 3, 'comment' => 'Decent program with mixed results. Some activities were engaging while others felt rushed. Average experience overall.'],
            ['role' => 'Student', 'rating' => 3, 'comment' => 'The event was fine. I learned some things but it could have been more interactive. It was what I expected, nothing more.'],
            ['role' => 'Staff', 'rating' => 2, 'comment' => 'Somewhat disorganized but the message was clear. Could use better planning and coordination for future events.'],
            ['role' => 'Community', 'rating' => 3, 'comment' => 'Not bad but not great either. Good intentions but the execution could be improved. Some activities were interesting, others were slow.'],
            ['role' => 'Community', 'rating' => 3, 'comment' => 'It served its purpose. The information was somewhat useful but presentation could be more engaging and accessible.'],
        ];

        foreach ($neutralFeedback as $feedback) {
            ActivityFeedback::create([
                'activity_id' => $neutralActivity->id,
                'role' => $feedback['role'],
                'rating' => $feedback['rating'],
                'comment' => $feedback['comment'],
                'source' => 'feedback_form',
            ]);
        }
    }
}
