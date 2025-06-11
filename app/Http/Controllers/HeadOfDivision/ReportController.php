<?php

namespace App\Http\Controllers\HeadOfDivision;

use App\Http\Controllers\Controller;
use App\Models\DivisionReport;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $reports = DivisionReport::where('division_id', Auth::user()->division_id)
            ->with(['project', 'creator', 'acknowledger', 'tasks']) // Tambahkan tasks
            ->latest()
            ->paginate(10);

        return view('head-of-division.reports.index', compact('reports'));
    }
    public function create()
    {
        $projects = Project::whereHas('tasks', function ($query) {
            $query->where('division_id', Auth::user()->division_id);
        })->orderBy('name')->get();

        // Get active tasks for current division
        $tasks = Task::where('division_id', Auth::user()->division_id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with('project')
            ->get();

        return view('head-of-division.reports.create', compact('projects', 'tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'report_type' => 'required|in:daily,weekly,monthly',
            'report_date' => 'required|date',
            'progress_summary' => 'required|string',
            'challenges' => 'nullable|string',
            'next_plan' => 'nullable|string',
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.progress' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120'
        ]);

        try {
            DB::beginTransaction();

            // Upload attachments if any
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('report-attachments', 'public');
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path
                    ];
                }
            }

            // Create report
            $report = DivisionReport::create([
                'project_id' => $validated['project_id'],
                'division_id' => Auth::user()->division_id,
                'report_number' => DivisionReport::generateReportNumber(),
                'report_date' => $validated['report_date'],
                'report_type' => $validated['report_type'],
                'progress_summary' => $validated['progress_summary'],
                'challenges' => $validated['challenges'],
                'next_plan' => $validated['next_plan'],
                'attachments' => $attachments,
                'created_by' => Auth::id()
            ]);

            // Attach tasks with their progress
            $taskData = collect($validated['tasks'])->mapWithKeys(function ($task) {
                return [$task['id'] => ['progress_notes' => $task['progress']]];
            })->toArray();

            $report->tasks()->attach($taskData);

            // Store task progress in JSON
            $report->task_progress = collect($validated['tasks'])->mapWithKeys(function ($task) {
                return [$task['id'] => $task['progress']];
            })->toArray();
            $report->related_tasks = array_column($validated['tasks'], 'id');
            $report->save();

            DB::commit();

            return redirect()
                ->route('head-of-division.reports.show', $report)
                ->with('success', 'Laporan berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(DivisionReport $report)
    {
        $report->load(['project', 'creator', 'acknowledger', 'tasks']);
        return view('head-of-division.reports.show', compact('report'));
    }

    public function edit(DivisionReport $report)
    {
        if ($report->acknowledged_by) {
            return back()->with('error', 'Laporan yang sudah dikonfirmasi tidak dapat diubah');
        }

        $projects = Project::whereHas('tasks', function ($query) {
            $query->where('division_id', Auth::user()->division_id);
        })->orderBy('name')->get();

        $tasks = Task::where('division_id', Auth::user()->division_id)
            ->whereIn('status', ['pending', 'in_progress'])
            ->with('project')
            ->get();

        return view('head-of-division.reports.edit', compact('report', 'projects', 'tasks'));
    }

    public function update(Request $request, DivisionReport $report)
    {
        if ($report->acknowledged_by) {
            return back()->with('error', 'Laporan yang sudah dikonfirmasi tidak dapat diubah');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'report_type' => 'required|in:daily,weekly,monthly',
            'report_date' => 'required|date',
            'progress_summary' => 'required|string',
            'challenges' => 'nullable|string',
            'next_plan' => 'nullable|string',
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.progress' => 'required|string',
            'attachments.*' => 'nullable|file|max:5120'
        ]);

        try {
            DB::beginTransaction();

            // Handle attachments
            $attachments = $report->attachments ?? [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('report-attachments', 'public');
                    $attachments[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $path
                    ];
                }
            }

            // Update report
            $report->update([
                'project_id' => $validated['project_id'],
                'report_date' => $validated['report_date'],
                'report_type' => $validated['report_type'],
                'progress_summary' => $validated['progress_summary'],
                'challenges' => $validated['challenges'],
                'next_plan' => $validated['next_plan'],
                'attachments' => $attachments
            ]);

            // Sync tasks with their progress
            $taskData = collect($validated['tasks'])->mapWithKeys(function ($task) {
                return [$task['id'] => ['progress_notes' => $task['progress']]];
            })->toArray();

            $report->tasks()->sync($taskData);

            // Update task progress in JSON
            $report->task_progress = collect($validated['tasks'])->mapWithKeys(function ($task) {
                return [$task['id'] => $task['progress']];
            })->toArray();
            $report->related_tasks = array_column($validated['tasks'], 'id');
            $report->save();

            DB::commit();

            return redirect()
                ->route('head-of-division.reports.show', $report)
                ->with('success', 'Laporan berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(DivisionReport $report)
    {
        if ($report->acknowledged_by) {
            return back()->with('error', 'Laporan yang sudah dikonfirmasi tidak dapat dihapus');
        }

        try {
            DB::beginTransaction();

            // Delete attachments
            if (!empty($report->attachments)) {
                foreach ($report->attachments as $attachment) {
                    Storage::disk('public')->delete($attachment['path']);
                }
            }

            $report->delete();

            DB::commit();

            return redirect()
                ->route('head-of-division.reports.index')
                ->with('success', 'Laporan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
