<?php

namespace App\Http\Controllers;

use App\Models\WebsiteContent;
use Illuminate\Http\Request;

class WebsiteContentController extends Controller
{
    public function index()
    {
        $contents = WebsiteContent::all();
        return view('ceso.website_content', compact('contents'));
    }

    public function update(Request $request)
    {
        foreach ($request->except(['_token', '_method']) as $key => $value) {

            if ($request->hasFile($key)) {
                $filePath = $request->file($key)->store('website', 'public');
                $value = $filePath;
            }

            WebsiteContent::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('ceso.website-content.index')
            ->with('success', 'Website content updated successfully.');
    }
}