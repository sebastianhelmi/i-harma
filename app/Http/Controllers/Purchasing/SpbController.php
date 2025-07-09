<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Spb;
use Illuminate\Http\Request;

class SpbController extends Controller
{
    public function index(Request $request)
    {
        $query = Spb::with(['project', 'requester', 'task', 'itemCategory'])
            ->where('status', 'approved')
            ->where('status_po', 'pending')
            ->when($request->search, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('spb_number', 'like', "%{$request->search}%")
                        ->orWhereHas('project', function ($q) use ($request) {
                            $q->where('name', 'like', "%{$request->search}%");
                        })
                        ->orWhereHas('requester', function ($q) use ($request) {
                            $q->where('name', 'like', "%{$request->search}%");
                        });
                });
            })
            ->when($request->project_id, function ($q) use ($request) {
                return $q->where('project_id', $request->project_id);
            })
            ->when($request->category_entry, function ($q) use ($request) {
                return $q->where('category_entry', $request->category_entry);
            })
            ->latest('spb_date');

        $spbs = $query->paginate(10, ['*'], 'pending_page');

        $spbHistories = Spb::with(['project', 'requester', 'task', 'itemCategory', 'po'])
            ->where('status_po', 'completed')
            ->latest('updated_at')
            ->paginate(10, ['*'], 'history_page');

        return view('purchasing.spbs.index', compact('spbs', 'spbHistories'));
    }

    public function show(Spb $spb)
    {

        $spb->load([
            'project',
            'requester',
            'task',
            'itemCategory',
            'approver',
            'siteItems',
            'workshopItems'
        ]);

        return view('purchasing.spbs.show', compact('spb'));
    }
}
