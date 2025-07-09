<?php

namespace App\Http\Controllers\HeadOfDivision;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Task;
use App\Models\Project;
use App\Models\WorkshopOutput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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
        $task = Task::with(['project', 'subtasks', 'subtasks.assignedTo', 'spb', 'workshopOutputs.workshopSpb'])
            ->findOrFail($id);

        // Check if task is assigned to current user
        if ($task->assigned_to !== Auth::id()) {
            return redirect()
                ->route('head-of-division.tasks.index')
                ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Check if SPB can be created:
        // - Task has project
        // - Task is not completed
        // - No existing SPB for this task
        $canCreateSpb = $task->project_id &&
            $task->status !== 'completed' &&
            !$task->spb()->exists();

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
            ->where(function ($query) {
                $query->whereNull('parent_task_id')  // Get parent tasks
                    ->orWhereHas('parentTask', function ($q) {  // Get subtasks where parent is assigned to user
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
                    'status_label' => match ($task->status) {
                        'pending' => 'Pending',
                        'in_progress' => 'Sedang Dikerjakan',
                        'completed' => 'Selesai',
                    }
                ];
            });

        return response()->json($tasks);
    }

    public function complete(Request $request, $id)
    {
        try {
            $task = Task::with(['spb'])->findOrFail($id);

            // Verify task ownership
            if ($task->assigned_to !== Auth::id()) {
                return back()->with('error', 'Anda tidak memiliki akses ke tugas ini.');
            }

            // Check if task has pending subtasks
            if ($task->subtasks()->where('status', '!=', 'completed')->exists()) {
                return back()->with('error', 'Selesaikan semua sub-tugas terlebih dahulu.');
            }

            // For workshop SPB, handle manual input of produced items
            if ($task->spb && $task->spb->category_entry === 'workshop') {
                $validated = $request->validate([
                    'outputs' => 'required|array|min:1',
                    'outputs.*.item_name' => 'required|string',
                    'outputs.*.quantity_produced' => 'required|integer|min:1',
                    'outputs.*.unit' => 'required|string',
                    'outputs.*.notes' => 'nullable|string'
                ]);

                DB::beginTransaction();

                try {
                    foreach ($validated['outputs'] as $output) {
                        // Create new inventory item for manual input
                        $inventory = Inventory::create([
                            'item_name' => $output['item_name'],
                            'unit' => $output['unit'],
                            'quantity' => $output['quantity_produced'],
                            'initial_stock' => $output['quantity_produced'],
                            'item_category_id' => $task->spb->item_category_id,
                            'added_by' => Auth::id()
                        ]);

                        // Find the corresponding workshop SPB item
                        $workshopSpbItem = $task->spb->workshopItems()
                            ->where('explanation_items', $output['item_name'])
                            ->where('unit', $output['unit'])
                            ->first();

                        // Record workshop production output
                        WorkshopOutput::create([
                            'task_id' => $task->id,
                            'spb_id' => $task->spb->id,
                            'workshop_spb_id' => $workshopSpbItem->id ?? null, // Assign the found workshop_spb_id
                            'inventory_id' => $inventory->id,
                            'quantity_produced' => $output['quantity_produced'],
                            'notes' => $output['notes'] ?? null,
                            'created_by' => Auth::id(),
                            'status' => 'completed',
                            'completed_at' => now()
                        ]);

                        // Record manual input transaction
                        InventoryTransaction::create([
                            'inventory_id' => $inventory->id,
                            'quantity' => $output['quantity_produced'],
                            'transaction_type' => 'IN',
                            'transaction_date' => now(),
                            'handled_by' => Auth::id(),
                            'remarks' => "Input manual hasil produksi workshop dari tugas #{$task->id}"
                        ]);
                    }

                    // Update task and SPB status
                    $task->spb->update(['status' => 'completed']);
                    $task->update(['status' => 'completed']);

                    DB::commit();

                    return redirect()
                        ->route('head-of-division.tasks.index')
                        ->with('success', 'Tugas workshop berhasil diselesaikan dan hasil produksi telah dicatat.');
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            // For non-workshop tasks
            $task->update(['status' => 'completed']);

            return redirect()
                ->route('head-of-division.tasks.index')
                ->with('success', 'Tugas berhasil diselesaikan.');
        } catch (\Exception $e) {
            Log::error('Error completing task:', [
                'task_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
