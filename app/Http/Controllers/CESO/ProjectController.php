<?php

namespace App\Http\Controllers\CESO;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::active()->latest()->paginate(10);
        return view('ceso.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('ceso.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'conducted_by' => 'required|string|max:255',
            'target_audience' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Proposed,Ongoing,Completed',
            'remarks' => 'nullable|string',
        ]);

        Project::create($validated);

        return redirect()->route('ceso.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        return view('ceso.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('ceso.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'conducted_by' => 'required|string|max:255',
            'target_audience' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Proposed,Ongoing,Completed',
            'remarks' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('ceso.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function archive(Project $project)
    {
        $project->update(['is_archived' => true]);

        return redirect()->route('ceso.projects.index')
            ->with('success', 'Project archived successfully.');
    }

    public function restore(Project $project)
    {
        $project->update(['is_archived' => false]);

        return redirect()->route('ceso.projects.index')
            ->with('success', 'Project restored successfully.');
    }

    public function archivedIndex()
    {
        $projects = Project::archived()->latest()->paginate(10);
        return view('ceso.projects.archived', compact('projects'));
    }
}
