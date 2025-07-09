<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    // public function __construct()
    // {
    //     Gate::authorize('manage-projects');
    // }

    public function index(Request $request)
    {
        $query = Task::whereHas('project', function ($q) {
            $q->where('manager_id', Auth::id());
        })->with(['project', 'assignedTo', 'subtasks']);

        // Apply filters
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Only get main tasks (not subtasks)
        $tasks = $query->whereNull('parent_task_id')
            ->latest()
            ->paginate(10);

        $projects = Project::where('manager_id', Auth::id())
            ->pluck('name', 'id');

        return view('pm.tasks.index', compact('tasks', 'projects'));
    }

    public function create(Request $request)
    {
        $projects = Project::where('manager_id', Auth::id())
            ->pluck('name', 'id');

        // Get project files
        $projectFiles = Project::where('manager_id', Auth::id())
            ->get()
            ->mapWithKeys(function ($project) {
                // No need to decode since it's already an array
                return [$project->id => $project->files ?? []];
            });

        $divisionHeads = User::whereHas('role', function ($q) {
            $q->where('name', 'Kepala Divisi');
        })->pluck('name', 'id');

        // Initialize project_id from request
        $project_id = $request->project_id;

        // Get parent task if creating a subtask
        $parentTask = null;
        if ($request->parent_id) {
            $parentTask = Task::findOrFail($request->parent_id);
            if ($parentTask->project->manager_id !== Auth::id()) {
                abort(403);
            }
            $project_id = $parentTask->project_id;
        }

        if ($project_id) {
            $project = Project::findOrFail($project_id);
            if ($project->manager_id !== Auth::id()) {
                abort(403);
            }
        }

        $parentTasks = collect();
        if ($project_id) {
            $parentTasks = Task::where('project_id', $project_id)
                ->whereNull('parent_task_id')
                ->pluck('name', 'id');
        }

        return view('pm.tasks.create', compact(
            'projects',
            'divisionHeads',
            'parentTask',
            'project_id',
            'parentTasks',
            'projectFiles'  // Add this line
        ));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'drawing_file' => 'required|string', // Add this line - we'll get it from project files
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date|after:today',
            'status' => 'required|in:pending,in_progress,completed',
            'parent_task_id' => 'nullable|exists:tasks,id'
        ]);

        // Verify project belongs to current manager
        $project = Project::findOrFail($validated['project_id']);
        if ($project->manager_id !== Auth::id()) {
            abort(403);
        }

        // Verify assigned user is a division head
        $assignedUser = User::findOrFail($validated['assigned_to']);
        if (!$assignedUser->isKepalaDivisi()) {
            return back()->with('error', 'Tasks can only be assigned to Division Heads.');
        }

        Task::create($validated);

        return redirect()
            ->route('pm.tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function update(Request $request, Task $task)
    {
        // Verify project belongs to current manager
        if ($task->project->manager_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'drawing_file' => 'required|string', // Add this line
            'project_id' => 'required|exists:projects,id',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'parent_task_id' => 'nullable|exists:tasks,id'
        ]);

        // Verify assigned user is a division head
        $assignedUser = User::findOrFail($validated['assigned_to']);
        if (!$assignedUser->isKepalaDivisi()) {
            return back()->with('error', 'Tasks can only be assigned to Division Heads.');
        }

        $task->update($validated);

        return redirect()
            ->route('pm.tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function edit(Task $task)
    {
        // Verify project belongs to current manager
        if ($task->project->manager_id !== Auth::id()) {
            abort(403);
        }

        $projects = Project::where('manager_id', Auth::id())
            ->pluck('name', 'id');

        $divisionHeads = User::whereHas('role', function ($q) {
            $q->where('name', 'Kepala Divisi');
        })->pluck('name', 'id');

        $parentTasks = Task::where('project_id', $task->project_id)
            ->whereNull('parent_task_id')
            ->where('id', '!=', $task->id)
            ->pluck('name', 'id');

        return view('pm.tasks.edit', compact('task', 'projects', 'divisionHeads', 'parentTasks'));
    }


    public function destroy(Task $task)
    {
        // Verify project belongs to current manager
        if ($task->project->manager_id !== Auth::id()) {
            abort(403);
        }

        $task->delete();

        return redirect()
            ->route('pm.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
