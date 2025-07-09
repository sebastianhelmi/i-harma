<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use App\Models\ProcurementHistory;
use App\Models\Spb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpbApprovalController extends Controller
{
    public function index(Request $request)
    {
        $spbs = Spb::with(['project', 'requester', 'task', 'itemCategory'])
            ->whereHas('project', function ($query) {
                $query->where('manager_id', Auth::id());
            })
            ->where('status', 'pending')
            ->latest('spb_date')
            ->paginate(10, ['*'], 'pending_page');

        $spbHistories = Spb::with(['project', 'requester', 'task', 'itemCategory'])
            ->whereHas('project', function ($query) {
                $query->where('manager_id', Auth::id());
            })
            ->whereIn('status', ['approved', 'rejected'])
            ->latest('approved_at')
            ->paginate(10, ['*'], 'history_page');

        return view('project-manager.spb-approvals.index', compact('spbs', 'spbHistories'));
    }

    public function show(Spb $spb)
    {
        // Verify authorization
        if ($spb->project->manager_id !== Auth::id()) {
            return redirect()
                ->route('pm.spb-approvals.index')
                ->with('error', 'Anda tidak memiliki akses ke SPB ini.');
        }

        $spb->load([
            'project',
            'requester',
            'task',
            'itemCategory',
            'siteItems',
            'workshopItems'
        ]);

        return view('project-manager.spb-approvals.show', compact('spb'));
    }

    public function approve(Request $request, Spb $spb)
    {
        // Verify authorization
        if ($spb->project->manager_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke SPB ini.');
        }

        $spb->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'status_po' => 'pending', // Set for Purchasing review
            'remarks' => $request->remarks
        ]);
        ProcurementHistory::create([
            'spb_id' => $spb->id,
            'document_type' => 'spb',
            'document_number' => $spb->spb_number,
            'status' => 'approved',
            'actor' => Auth::id(),
            'description' => 'SPB telah disetujui'
        ]);

        return redirect()
            ->route('pm.spb-approvals.index')
            ->with('success', "SPB {$spb->spb_number} telah disetujui.");
    }

    public function reject(Request $request, Spb $spb)
    {
        // Verify authorization
        if ($spb->project->manager_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke SPB ini.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $spb->update([
            'status' => 'rejected',
            'remarks' => $validated['rejection_reason']
        ]);

        return redirect()
            ->route('pm.spb-approvals.index')
            ->with('success', "SPB {$spb->spb_number} telah ditolak.");
    }
}
