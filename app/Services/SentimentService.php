<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class SentimentService
{
    public function analyze(string $feedback): string
    {
        $response = OpenAI::responses()->create([
            'model' => 'gpt-4.1-mini',
            'input' => "Classify this feedback as Positive, Neutral, or Negative only:\n\n{$feedback}"
        ]);

        return trim($response->output[0]->content[0]->text);
    }
}