<?php

namespace App\Http\Controllers\HeadOfDivision;

use App\Http\Controllers\Controller;
use App\Models\Spb;
use App\Models\Project;
use App\Models\Task;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpbController extends Controller
{
    public function index(Request $request)
    {
        $query = Spb::with(['project', 'task', 'itemCategory'])
            ->where('requested_by', Auth::id());

        // Apply search filter
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('spb_number', 'like', "%{$request->search}%")
                    ->orWhereHas('project', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%");
                    });
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by project
        if ($request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        // Get all projects for filter dropdown
        $projects = Project::whereHas('tasks', function ($query) {
            $query->where('assigned_to', Auth::id());
        })->pluck('name', 'id');

        $spbs = $query->latest()->paginate(10);

        return view('head-of-division.spbs.index', compact('spbs', 'projects'));
    }

    public function create(?Task $task = null)
    {
        // If task is provided, verify it belongs to current user
        if ($task) {
            if ($task->assigned_to !== Auth::id()) {
                return redirect()
                    ->route('head-of-division.tasks.index')
                    ->with('error', 'Anda tidak memiliki akses ke tugas ini.');
            }

            // Verify task is not completed
            if ($task->status === 'completed') {
                return redirect()
                    ->route('head-of-division.tasks.show', $task)
                    ->with('error', 'Tidak dapat membuat SPB untuk tugas yang sudah selesai.');
            }
        }

        // Get projects where user has tasks
        $projects = Project::whereHas('tasks', function ($query) {
            $query->where('assigned_to', Auth::id());
        })->pluck('name', 'id');

        // Get tasks if project_id is provided
        $tasks = collect();
        if ($task) {
            $tasks = Task::where('project_id', $task->project_id)
                ->where('assigned_to', Auth::id())
                ->where('status', '!=', 'completed')
                ->pluck('name', 'id');
        }

        // Get item categories
        $itemCategories = ItemCategory::pluck('name', 'id');

        return view('head-of-division.spbs.create', compact(
            'projects',
            'tasks',
            'itemCategories',
            'task'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spb_date' => 'required|date',
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'required|exists:tasks,id',
            'item_category_id' => 'required|exists:item_categories,id',
            'category_entry' => 'required|in:site,workshop',
            'remarks' => 'nullable|string',

            // Site items validation
            'site_items' => 'required_if:category_entry,site|array|min:1',
            'site_items.*.item_name' => 'required_if:category_entry,site|string',
            'site_items.*.quantity' => 'required_if:category_entry,site|integer|min:1',
            'site_items.*.unit' => 'required_if:category_entry,site|string',
            'site_items.*.information' => 'required_if:category_entry,site|string',
            'site_items.*.document_file.*' => 'nullable|file|max:10240', // max 10MB

            // Workshop items validation
            'workshop_items' => 'required_if:category_entry,workshop|array|min:1',
            'workshop_items.*.explanation_items' => 'required_if:category_entry,workshop|string',
            'workshop_items.*.quantity' => 'required_if:category_entry,workshop|integer|min:1',
            'workshop_items.*.unit' => 'required_if:category_entry,workshop|string',
        ]);

        // Verify task belongs to current user
        $task = Task::findOrFail($validated['task_id']);
        if ($task->assigned_to !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke tugas ini.');
        }

        // Create SPB
        $spb = Spb::create([
            'spb_number' => Spb::generateSpbNumber(),
            'spb_date' => $validated['spb_date'],
            'project_id' => $validated['project_id'],
            'task_id' => $validated['task_id'],
            'item_category_id' => $validated['item_category_id'],
            'category_entry' => $validated['category_entry'],
            'remarks' => $validated['remarks'],
            'requested_by' => Auth::id(),
            'status' => 'pending',
            'status_po' => 'waiting'
        ]);

        // Store items based on category
        if ($validated['category_entry'] === 'site') {
            foreach ($validated['site_items'] as $item) {
                $files = [];
                if (isset($item['document_file'])) {
                    foreach ($item['document_file'] as $file) {
                        $path = $file->store('spb-documents', 'public');
                        $files[] = $path;
                    }
                }

                $spb->siteItems()->create([
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit'],
                    'information' => $item['information'],
                    'document_file' => $files
                ]);
            }
        } else {
            foreach ($validated['workshop_items'] as $item) {
                $spb->workshopItems()->create([
                    'explanation_items' => $item['explanation_items'],
                    'quantity' => $item['quantity'],
                    'unit' => $item['unit']
                ]);
            }
        }

        return redirect()
            ->route('head-of-division.spbs.index')
            ->with('success', 'SPB berhasil dibuat.');
    }

    public function show(Spb $spb)
    {
        // Verify ownership
        if ($spb->requested_by !== Auth::id()) {
            return redirect()
                ->route('head-of-division.spbs.index')
                ->with('error', 'Anda tidak memiliki akses ke SPB ini.');
        }

        // Load relationships
        $spb->load([
            'project',
            'task',
            'itemCategory',
            'requester',
            'approver',
            'siteItems',
            'workshopItems'
        ]);

        return view('head-of-division.spbs.show', compact('spb'));
    }
}
