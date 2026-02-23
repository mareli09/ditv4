<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of published announcements for the community
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Announcement::published();

        if ($search) {
            $query = $query->where('title', 'like', "%$search%")
                           ->orWhere('content', 'like', "%$search%");
        }

        $announcements = $query->orderBy('published_at', 'desc')->paginate(10);

        return view('announcements.index', [
            'announcements' => $announcements,
            'search' => $search,
        ]);
    }

    /**
     * Display the specified announcement
     */
    public function show(Announcement $announcement)
    {
        if ($announcement->status !== 'published') {
            abort(404);
        }

        return view('announcements.show', ['announcement' => $announcement]);
    }
}
