<?php

namespace App\Http\Controllers\HeadOfDivision;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use App\Models\Spb;
use App\Models\DivisionReport;
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
        $divisionId = $user->division_id;

        // Summary
        $totalProjects = Project::whereHas('tasks', function($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            })->count();
        $completedTasksThisMonth = Task::where('division_id', $divisionId)
            ->where('status', 'completed')
            ->whereBetween('updated_at', [$startOfMonth, $endOfMonth])
            ->count();
        $activeTasks = Task::where('division_id', $divisionId)
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
        $pendingSpb = Spb::whereHas('task', function($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            })
            ->where('status', 'pending')
            ->count();
        $reportsThisMonth = DivisionReport::where('division_id', $divisionId)
            ->whereBetween('report_date', [$startOfMonth, $endOfMonth])
            ->count();

        // Project Progress
        $projects = Project::whereHas('tasks', function($q) use ($divisionId) {
                $q->where('division_id', $divisionId);
            })
            ->with(['tasks' => function($q) use ($divisionId) {
                $q->where('division_id', $divisionId)->select('id', 'project_id', 'status');
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
        $tasksThisWeek = Task::where('division_id', $divisionId)
            ->whereBetween('due_date', [$startOfWeek, $endOfWeek])
            ->with(['project', 'assignedTo'])
            ->orderBy('due_date')
            ->get();

        // Recent notifications (last 5 unread)
        $notifications = $user->unreadNotifications->sortByDesc('created_at')->take(5);

        return view('head-of-division.dashboard', compact(
            'totalProjects',
            'completedTasksThisMonth',
            'activeTasks',
            'pendingSpb',
            'reportsThisMonth',
            'projectProgress',
            'tasksThisWeek',
            'notifications'
        ));
    }
}
