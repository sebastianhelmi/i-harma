<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    // public function __construct()
    // {
    //     // Use Gate::authorize to check role
    //     Gate::authorize('manage-projects');
    // }

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
            'client_name' => 'required|string|max:255',
            'project_location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:pending,ongoing,completed',
            'files.*' => 'nullable|file|max:10240', // Max 10MB per file
            'contract_document' => 'nullable|file|max:10240'
        ]);

        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('projects', 'public');
                $files[] = $path;
            }
        }

        $contractDocumentPath = null;
        if ($request->hasFile('contract_document')) {
            $contractDocumentPath = $request->file('contract_document')->store('contracts', 'public');
        }

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'client_name' => $validated['client_name'],
            'project_location' => $validated['project_location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'manager_id' => Auth::id(),
            'files' => $files,
            'contract_document' => $contractDocumentPath
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
            'client_name' => 'required|string|max:255',
            'project_location' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'required|in:pending,ongoing,completed',
            'files.*' => 'nullable|file|max:10240',
            'contract_document' => 'nullable|file|max:10240'
        ]);

        $files = $project->files ?? [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('projects', 'public');
                $files[] = $path;
            }
        }

        $contractDocumentPath = $project->contract_document;
        if ($request->hasFile('contract_document')) {
            // Delete old contract document if it exists
            if ($project->contract_document) {
                Storage::disk('public')->delete($project->contract_document);
            }
            $contractDocumentPath = $request->file('contract_document')->store('contracts', 'public');
        }

        $project->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'client_name' => $validated['client_name'],
            'project_location' => $validated['project_location'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'status' => $validated['status'],
            'files' => $files,
            'contract_document' => $contractDocumentPath
        ]);

        return redirect()
            ->route('pm.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function show(Project $project)
    {
        // Check if user can view this project
        if (! Gate::allows('view', $project)) {
            abort(403);
        }

        $project->load(['tasks.subtasks']);

        return view('pm.projects.show', compact('project'));
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

        // Delete contract document
        if ($project->contract_document) {
            Storage::disk('public')->delete($project->contract_document);
        }

        $project->delete();

        return redirect()
            ->route('pm.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function exportExcel(Project $project)
    {
        if ($project->status !== 'completed') {
            abort(403, 'Project must be completed to export S-Curve.');
        }

        $project->load(['tasks.subtasks']);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header
        $headers = ['DESCRIPTION', 'VOL.', 'UNIT', 'VALUE'];
        for ($i = 1; $i <= 52; $i++) {
            $headers[] = 'W' . $i;
        }
        $sheet->fromArray($headers, NULL, 'A1');

        $row = 2;
        $mainTasksCount = $project->tasks->whereNull('parent_task_id')->count();

        foreach ($project->tasks as $task) {
            if ($task->parent_task_id === null) {
                // Main Task
                $mainTaskWeight = $mainTasksCount > 0 ? 100 / $mainTasksCount : 0;
                $taskRow = [
                    $task->name,
                    '', // VOL.
                    '', // UNIT
                    number_format($mainTaskWeight, 2) . '%', // VALUE
                ];

                // Add progress for each week (simplified: 100% if completed, 0% otherwise)
                for ($i = 1; $i <= 52; $i++) {
                    $taskRow[] = $task->status === 'completed' ? number_format($mainTaskWeight, 2) . '%' : '';
                }
                $sheet->fromArray($taskRow, NULL, 'A' . $row++);

                // Subtasks
                $subtasks = $task->subtasks;
                if ($subtasks->count() > 0) {
                    $subtaskWeight = $mainTaskWeight / $subtasks->count();
                    foreach ($subtasks as $subtask) {
                        $subtaskRow = [
                            ' - ' . $subtask->name,
                            '', // VOL.
                            '', // UNIT
                            number_format($subtaskWeight, 2) . '%', // VALUE
                        ];
                        for ($i = 1; $i <= 52; $i++) {
                            $subtaskRow[] = $subtask->status === 'completed' ? number_format($subtaskWeight, 2) . '%' : '';
                        }
                        $sheet->fromArray($subtaskRow, NULL, 'A' . $row++);
                    }
                }
            }
        }

        // Set total percentage
        $sheet->setCellValue('A' . $row, 'Total Percentage');
        $sheet->setCellValue('D' . $row, '100%');

        $writer = new Xlsx($spreadsheet);
        $filename = 'S-Curve-' . Str::slug($project->name) . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
