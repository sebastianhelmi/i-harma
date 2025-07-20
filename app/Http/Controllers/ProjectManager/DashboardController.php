<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\Spb;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();

        // Summary
        $totalProjects = Project::where('manager_id', $user->id)
            ->where('status', '!=', 'completed')
            ->count();
        $completedTasksThisMonth = Task::whereHas('project', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->count();
        $activeTasks = Task::whereHas('project', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
        $pendingSpb = Spb::whereHas('project', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })
            ->where('status', 'pending')
            ->count();

        // Project Progress
        $projects = Project::where('manager_id', $user->id)
            ->with(['tasks' => function($q) {
                $q->select('id', 'project_id', 'status');
            }])
            ->get();
        $projectProgress = $projects->map(function($project) {
            $total = $project->tasks->count();
            $completed = $project->tasks->where('status', 'completed')->count();
            $progress = $total > 0 ? round(($completed / $total) * 100) : 0;
            $status = $project->status;
            return [
                'name' => $project->name,
                'progress' => $progress,
                'status' => $status,
            ];
        });

        // Tasks deadline this week
        $tasksThisWeek = Task::whereHas('project', function($q) use ($user) {
                $q->where('manager_id', $user->id);
            })
            ->whereBetween('due_date', [$startOfWeek, $endOfWeek])
            ->with(['project', 'assignedTo'])
            ->orderBy('due_date')
            ->get();

        // Recent notifications (last 5 unread)
        $notifications = $user->unreadNotifications->sortByDesc('created_at')->take(5);

        return view('pm.dashboard', compact(
            'totalProjects',
            'completedTasksThisMonth',
            'activeTasks',
            'pendingSpb',
            'projectProgress',
            'tasksThisWeek',
            'notifications'
        ));
    }
}
