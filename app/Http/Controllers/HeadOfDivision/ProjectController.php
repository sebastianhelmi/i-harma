<?php

namespace App\Http\Controllers\HeadOfDivision;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    protected $middleware = ['role:Kepala Divisi'];

    public function index(Request $request)
    {
        $query = Project::whereHas('tasks', function ($query) {
            $query->where('assigned_to', Auth::id());
        })->with(['manager', 'tasks' => function ($query) {
            $query->where('assigned_to', Auth::id());
        }]);

        // Apply search if provided
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by status if provided
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $projects = $query->distinct()->latest()->paginate(10);

        return view('head-of-division.projects.index', compact('projects'));
    }

        public function show($id)
    {
        $project = Project::with(['manager', 'tasks' => function ($query) {
            $query->where('assigned_to', Auth::id());
        }])->findOrFail($id);

        // Verify user has tasks in this project
        if (!$project->tasks->count()) {
            return redirect()
                ->route('head-of-division.projects.index')
                ->with('error', 'Anda tidak memiliki akses ke proyek ini.');
        }

        return view('head-of-division.projects.show', compact('project'));
    }
}
