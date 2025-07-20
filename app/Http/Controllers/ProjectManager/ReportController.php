<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Spb;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;
use App\Models\DivisionReport;

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

    public function show(DivisionReport $report)
    {
        $report->load(['project', 'creator', 'acknowledger', 'tasks']);
        return view('pm.reports.show', compact('report'));
    }

    public function exportExcel(Request $request)
    {
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $headers = ['Nama Proyek', 'Dokumen Kontrak', 'Jumlah SPB', 'Progress Tugas'];
        $sheet->fromArray($headers, NULL, 'A1');

        $row = 2;
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

            $contractDocument = $project->contract_document ? Storage::url($project->contract_document) : 'Tidak Ada';

            $rowData = [
                $project->name,
                $contractDocument,
                $spbs->count() . ' SPB',
                $taskProgress . '% (' . $completedTasks . ' dari ' . $totalTasks . ' tugas selesai)',
            ];
            $sheet->fromArray($rowData, NULL, 'A' . $row++);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan-Proyek-' . now()->format('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
