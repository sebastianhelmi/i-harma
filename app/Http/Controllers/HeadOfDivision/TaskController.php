<?php

namespace App\Http\Controllers\HeadOfDivision;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $query = Task::with(['project', 'subtasks'])
            ->where('assigned_to', Auth::id());

        // Apply search filter
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by project
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Get main tasks only (not subtasks)
        $tasks = $query->whereNull('parent_task_id')
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        // Get projects for filter dropdown
        $projects = Project::whereHas('tasks', function ($q) {
            $q->where('assigned_to', Auth::id());
        })->pluck('name', 'id');

        return view('head-of-division.tasks.index', compact('tasks', 'projects'));
    }


    public function show($id)
    {
        $task = Task::with(['project', 'subtasks', 'subtasks.assignedTo'])
            ->findOrFail($id);

        // Check if task is assigned to current user
        if ($task->assigned_to !== Auth::id()) {
            return redirect()
                ->route('head-of-division.tasks.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Check if SPB can be created
        $canCreateSpb = $task->project_id && $task->status !== 'completed';

        return view('head-of-division.tasks.show', compact('task', 'canCreateSpb'));
    }

    public function create()
    {
        $task = Task::with(['project', 'subtasks'])
            ->where('assigned_to', Auth::id())
            ->firstOrFail();

        return view('head-of-division.tasks.create', compact('task'));
    }
     public function getProjectTasks(Project $project)
    {
        // Verify user has access to this project's tasks
        if (!$project->tasks()->where('assigned_to', Auth::id())->exists()) {
            return response()->json([
                'error' => 'Anda tidak memiliki akses ke proyek ini.'
            ], 403);
        }

        // Get all tasks (including subtasks) assigned to current user in this project
        $tasks = Task::where('project_id', $project->id)
            ->where('assigned_to', Auth::id())
            ->where(function($query) {
                $query->whereNull('parent_task_id')  // Get parent tasks
                      ->orWhereHas('parentTask', function($q) {  // Get subtasks where parent is assigned to user
                          $q->where('assigned_to', Auth::id());
                      });
            })
            ->select('id', 'name', 'due_date', 'status', 'parent_task_id')
            ->with(['parentTask:id,name'])
            ->orderBy('parent_task_id', 'asc')
            ->orderBy('due_date', 'asc')
            ->get()
            ->map(function ($task) {
                $taskName = $task->parent_task_id
                    ? '↳ ' . $task->name . ' (' . $task->parentTask->name . ')'  // Prefix subtasks with arrow
                    : $task->name;

                return [
                    'id' => $task->id,
                    'name' => $taskName,
                    'due_date' => $task->due_date,
                    'status' => $task->status,
                    'status_label' => match($task->status) {
                        'pending' => 'Pending',
                        'in_progress' => 'Sedang Dikerjakan',
                        'completed' => 'Selesai',
                    }
                ];
            });

        return response()->json($tasks);
    }
}
