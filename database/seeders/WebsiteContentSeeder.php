<?php

namespace Database\Seeders;

use App\Models\WebsiteContent;
use Illuminate\Database\Seeder;

class WebsiteContentSeeder extends Seeder
{
    public function run()
    {
        $contents = [
            ['key' => 'about_description', 'value' => 'The Community Extension Services Office (CESO) is dedicated to providing quality extension programs and services to our community. Our mission is to empower individuals and organizations through education, innovation, and partnership.'],
            ['key' => 'mission', 'value' => 'To provide accessible and high-quality extension services that promote sustainable development and improve the quality of life in our communities.'],
            ['key' => 'vision', 'value' => 'An empowered and progressive community equipped with knowledge and skills to achieve sustainable development and social transformation.'],
            ['key' => 'cta_heading', 'value' => 'Want to be part of the community?'],
            ['key' => 'cta_description', 'value' => 'Join our extension programs and make a difference in your community. Together, we can create positive change and build a better future.'],
            ['key' => 'cta_button_text', 'value' => 'Join Us Today'],
            ['key' => 'contact_email', 'value' => 'ceso@example.edu.ph'],
            ['key' => 'contact_number', 'value' => '+63 900 000 0000'],
            ['key' => 'contact_address', 'value' => 'Community Extension Services Office, Sample City, Philippines'],
            ['key' => 'footer_copyright', 'value' => '© 2026 Community Extension Services Office. All rights reserved.'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com/ceso'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/ceso'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com/@ceso'],
            ['key' => 'privacy_policy', 'value' => 'Privacy Policy - We respect your privacy and protect your personal information. Your data is handled securely and in accordance with applicable privacy laws.'],
            ['key' => 'terms_of_service', 'value' => 'Terms of Service - By accessing our website, you agree to comply with these terms and conditions. Please review them carefully.'],
            ['key' => 'accessibility', 'value' => 'Accessibility - We are committed to making our website accessible to all users, including those with disabilities.'],
            ['key' => 'carousel_images', 'value' => '["https://via.placeholder.com/1200x400?text=CESO+Activity+1", "https://via.placeholder.com/1200x400?text=CESO+Activity+2", "https://via.placeholder.com/1200x400?text=CESO+Activity+3"]'],
            ['key' => 'news_1', 'value' => 'CESO Launches New Community Development Program'],
            ['key' => 'news_2', 'value' => 'Successful Training Workshop on Sustainable Agriculture Held'],
            ['key' => 'news_3', 'value' => 'CESO Recognizes Outstanding Community Partners'],
        ];

        foreach ($contents as $content) {
            WebsiteContent::updateOrCreate(
                ['key' => $content['key']],
                ['value' => $content['value']]
            );
        }
    }
}
