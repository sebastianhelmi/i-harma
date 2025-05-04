<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function __construct()
    {
        // Use Gate::authorize to check role
        Gate::authorize('manage-projects');
    }

    public function index()
    {
        $projects = Project::where('manager_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pm.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('pm.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:pending,ongoing,completed',
            'files.*' => 'nullable|file|max:10240' // Max 10MB per file
        ]);

        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('projects', 'public');
                $files[] = $path;
            }
        }

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'manager_id' => Auth::id(),
            'files' => $files
        ]);

        return redirect()
            ->route('pm.projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        // Check if user can update this project
        if (! Gate::allows('update', $project)) {
            abort(403);
        }

        return view('pm.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        // Check if user can update this project
        if (! Gate::allows('update', $project)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:pending,ongoing,completed',
            'files.*' => 'nullable|file|max:10240'
        ]);

        $files = $project->files ?? [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('projects', 'public');
                $files[] = $path;
            }
        }

        $project->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'files' => $files
        ]);

        return redirect()
            ->route('pm.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        // Check if user can delete this project
        if (! Gate::allows('delete', $project)) {
            abort(403);
        }

        // Delete associated files
        if (!empty($project->files)) {
            foreach ($project->files as $file) {
                Storage::disk('public')->delete($file);
            }
        }

        $project->delete();

        return redirect()
            ->route('pm.projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
