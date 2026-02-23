<?php

namespace App\Http\Controllers\CESO;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class CESOAnnouncementController extends Controller
{
    /**
     * Display a listing of announcements
     */
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $search = $request->input('search');
        $status = $request->input('status');

        $query = Announcement::query();

        if ($search) {
            $query = $query->where('title', 'like', "%$search%")
                           ->orWhere('content', 'like', "%$search%");
        }

        if ($status) {
            $query = $query->where('status', $status);
        }

        $announcements = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('ceso.announcements.index', [
            'announcements' => $announcements,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new announcement
     */
    public function create()
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        return view('ceso.announcements.create');
    }

    /**
     * Store a newly created announcement
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Announcement::create($validated);

        return redirect()->route('ceso.announcements.index')
                        ->with('success', 'Announcement created successfully.');
    }

    /**
     * Display the specified announcement
     */
    public function show(Announcement $announcement)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        return view('ceso.announcements.show', ['announcement' => $announcement]);
    }

    /**
     * Show the form for editing the specified announcement
     */
    public function edit(Announcement $announcement)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        return view('ceso.announcements.edit', ['announcement' => $announcement]);
    }

    /**
     * Update the specified announcement
     */
    public function update(Request $request, Announcement $announcement)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['updated_by'] = auth()->id();

        // Set published_at when transitioning from draft to published
        if ($announcement->status === 'draft' && $validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $announcement->update($validated);

        return redirect()->route('ceso.announcements.index')
                        ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Archive an announcement
     */
    public function archive(Announcement $announcement)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $announcement->update([
            'status' => 'archived',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Announcement archived successfully.');
    }

    /**
     * Restore an archived announcement
     */
    public function restore(Announcement $announcement)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $announcement->update([
            'status' => 'published',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Announcement restored successfully.');
    }

    /**
     * Delete an announcement permanently
     */
    public function destroy(Announcement $announcement)
    {
        if (auth()->user()->role !== 'CESO') abort(403);

        $announcement->forceDelete();

        return redirect()->back()->with('success', 'Announcement deleted permanently.');
    }
}
