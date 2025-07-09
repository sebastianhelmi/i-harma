<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Spb;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $projectsData = collect();

        if ($request->has('start_date') && $request->has('end_date')) {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $projects = Project::where('manager_id', Auth::id())
                ->whereBetween('start_date', [$startDate, $endDate]) // Assuming project start_date is relevant
                ->orWhereBetween('end_date', [$startDate, $endDate]) // Or project end_date
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $startDate)
                          ->where('end_date', '>=', $endDate);
                })
                ->with(['spbs', 'tasks'])
                ->get();

            foreach ($projects as $project) {
                $spbs = Spb::where('project_id', $project->id)
                    ->whereBetween('spb_date', [$startDate, $endDate])
                    ->get();

                $tasks = Task::where('project_id', $project->id)
                    ->whereBetween('due_date', [$startDate, $endDate])
                    ->get();

                // Calculate task progress (simple example: completed tasks / total tasks)
                $totalTasks = $tasks->count();
                $completedTasks = $tasks->where('status', 'completed')->count();
                $taskProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

                $projectsData->push([
                    'project' => $project,
                    'spbs' => $spbs,
                    'tasks' => $tasks,
                    'task_progress' => $taskProgress,
                ]);
            }
        }

        return view('pm.reports.index', compact('projectsData'));
    }
}
