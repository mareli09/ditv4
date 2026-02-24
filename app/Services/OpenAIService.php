<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    private $apiKey;
    private $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    /**
     * Generate dashboard insights from project and activity data
     */
    public function generateDashboardInsights($projectStats, $activityStats, $projectData, $activityData)
    {
        $prompt = $this->buildDashboardPrompt($projectStats, $activityStats, $projectData, $activityData);

        return $this->callOpenAI($prompt);
    }

    /**
     * Build the prompt for dashboard analysis
     */
    private function buildDashboardPrompt($projectStats, $activityStats, $projectData, $activityData)
    {
        return <<<PROMPT
You are an expert CESO (Civic Engagement and Social Outreach) dashboard analyst. Analyze the following project and activity data and provide actionable insights.

PROJECT STATISTICS:
- Total Projects: {$projectStats['total']}
- Active Projects: {$projectStats['active']}
- Completed Projects: {$projectStats['completed']}
- Team Members in Projects: {$projectStats['totalMembers']}

ACTIVITY STATISTICS:
- Total Activities: {$activityStats['total']}
- Upcoming Activities: {$activityStats['upcoming']}
- Ongoing Activities: {$activityStats['ongoing']}
- Completed Activities: {$activityStats['completed']}
- Total Participants: {$activityStats['totalParticipants']}
- Average Feedback Rating: {$activityStats['avgRating']}

PROJECT ENGAGEMENT:
{$this->formatProjectData($projectData)}

ACTIVITY ENGAGEMENT:
{$this->formatActivityData($activityData)}

Please provide the following in JSON format:
{
  "summary": "Brief executive summary of overall performance",
  "alerts": ["List of important alerts or concerns"],
  "improvements": ["Specific areas that need improvement"],
  "suggestions": ["Actionable recommendations"],
  "highlights": ["Key achievements to celebrate"],
  "nextSteps": ["Recommended next actions"]
}

Focus on CESO's community engagement effectiveness and impact.
PROMPT;
    }

    /**
     * Format project data for the prompt
     */
    private function formatProjectData($projectData)
    {
        $formatted = [];
        foreach ($projectData as $project) {
            $formatted[] = "- {$project['title']}: {$project['status']} ({$project['members']} members)";
        }
        return implode("\n", $formatted);
    }

    /**
     * Format activity data for the prompt
     */
    private function formatActivityData($activityData)
    {
        $formatted = [];
        foreach ($activityData as $activity) {
            $formatted[] = "- {$activity['title']}: {$activity['status']} ({$activity['participants']} participants, Rating: {$activity['rating']}/5)";
        }
        return implode("\n", $formatted);
    }

    /**
     * Call OpenAI API
     */
    private function callOpenAI($prompt)
    {
        try {
            $response = Http::withToken($this->apiKey)
                ->timeout(30)
                ->post("{$this->baseUrl}/chat/completions", [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert CESO dashboard analyst providing strategic insights on community engagement programs.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 1500
                ]);

            if ($response->successful()) {
                $content = $response->json()['choices'][0]['message']['content'];
                
                // Parse JSON from response
                preg_match('/\{[\s\S]*\}/', $content, $matches);
                if (isset($matches[0])) {
                    return json_decode($matches[0], true);
                }
            }

            return $this->getDefaultInsights();
        } catch (\Exception $e) {
            \Log::error('OpenAI API Error: ' . $e->getMessage());
            return $this->getDefaultInsights();
        }
    }

    /**
     * Return default insights if API fails
     */
    private function getDefaultInsights()
    {
        return [
            'summary' => 'Dashboard analysis unavailable at the moment. Please try again later.',
            'alerts' => ['Unable to connect to AI service'],
            'improvements' => [],
            'suggestions' => ['Review activity participation rates', 'Evaluate project completion timelines'],
            'highlights' => [],
            'nextSteps' => ['Check API connection', 'Verify OpenAI credentials']
        ];
    }
}
