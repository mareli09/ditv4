<?php

namespace App\Http\Controllers;

use App\Models\WebsiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import Validator facade

class WebsiteContentController extends Controller
{
    public function index()
    {
        $rawContents = WebsiteContent::all();

        $contents = $rawContents->groupBy('key')->map(function ($item) {
            return $item->first()->value;
        })->toArray();

        $contents['privacy_policy'] = $contents['privacy_policy'] ?? '';
        $contents['terms_of_service'] = $contents['terms_of_service'] ?? '';
        $contents['accessibility'] = $contents['accessibility'] ?? '';

        return view('ceso.website_content', compact('contents'));
    }

    public function update(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            '*' => 'nullable|string', // Allow null or string for all fields
        ])->validate();

        foreach ($validatedData as $key => $value) {
            if (in_array($key, ['_token', '_method'])) {
                continue;
            }

            if ($request->hasFile($key)) {
                $filePath = $request->file($key)->store('website', 'public');
                $value = $filePath;
            }

            if (!is_null($value)) { // Ensure value is not null before updating
                WebsiteContent::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->route('ceso.website-content.index')
            ->with('success', 'Website content updated successfully.');
    }

    public function updateHero(Request $request)
    {
        $images = [];
        for ($i = 1; $i <= 3; $i++) {
            if ($request->hasFile("hero_image_$i")) {
                $filePath = $request->file("hero_image_$i")->store('website', 'public');
                $images[] = $filePath;
            } elseif ($request->has("hero_url_$i") && !empty($request->input("hero_url_$i"))) {
                $images[] = $request->input("hero_url_$i");
            }
        }

        if (!empty($images)) {
            WebsiteContent::updateOrCreate(
                ['key' => 'carousel_images'],
                ['value' => json_encode($images)]
            );
        }

        return redirect()->route('ceso.website.index')->with('success', 'Hero section updated successfully.');
    }

    public function updateAbout(Request $request)
    {
        $this->updateSection($request, ['about_description', 'mission', 'vision']);
        return redirect()->route('ceso.website.index')->with('success', 'About section updated successfully.');
    }

    public function updateNews(Request $request)
    {
        $this->updateSection($request, ['news_1', 'news_2', 'news_3']);
        return redirect()->route('ceso.website.index')->with('success', 'News section updated successfully.');
    }

    public function updateCTA(Request $request)
    {
        $this->updateSection($request, ['cta_heading', 'cta_description', 'cta_button_text']);
        return redirect()->route('ceso.website.index')->with('success', 'Call-to-Action section updated successfully.');
    }

    public function updateContact(Request $request)
    {
        $this->updateSection($request, ['contact_email', 'contact_number', 'contact_address']);
        return redirect()->route('ceso.website.index')->with('success', 'Contact section updated successfully.');
    }

    public function updateFooter(Request $request)
    {
        $this->updateSection($request, ['privacy_policy', 'terms_of_service', 'accessibility', 'footer_copyright', 'facebook_url', 'instagram_url', 'youtube_url']);
        return redirect()->route('ceso.website.index')->with('success', 'Footer section updated successfully.');
    }

    private function updateSection(Request $request, array $keys)
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                $value = $request->input($key);

                if (!is_null($value)) { // Ensure value is not null before updating
                    WebsiteContent::updateOrCreate(
                        ['key' => $key],
                        ['value' => $value]
                    );
                }
            }
        }
    }
}