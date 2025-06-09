<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPlan;
use App\Models\Inventory;
use App\Models\WorkshopOutput;
use App\Models\SiteSpb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryPlanItemController extends Controller
{
    public function create(DeliveryPlan $plan)
    {
        if (!$plan->canBeUpdated()) {
            return back()->with('error', 'Rencana pengiriman tidak dapat diubah');
        }

        // Get inventory items that have SPB history
        $inventoryItems = Inventory::with(['itemCategory'])
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('site_spbs')
                    ->join('spbs', 'site_spbs.spb_id', '=', 'spbs.id')
                    ->whereColumn('site_spbs.item_name', 'inventories.item_name')
                    ->whereNull('site_spbs.delivery_plan_id')
                    ->where('spbs.status', 'approved');
            })
            ->where('quantity', '>', 0)
            ->get();

        $workshopOutputs = WorkshopOutput::with(['workshopSpb', 'spb'])
            ->where('status', 'completed')
            ->whereNull('delivery_plan_id')
            ->get();

        $siteSpbItems = SiteSpb::with('spb')
            ->whereHas('spb', function ($query) {
                $query->where('status', 'approved');
            })
            ->whereNull('delivery_plan_id')
            ->get();

        return view('delivery.plans.items.create', compact(
            'plan',
            'inventoryItems',
            'workshopOutputs',
            'siteSpbItems'
        ));
    }

    public function store(Request $request, DeliveryPlan $plan)
    {
        if (!$plan->canBeUpdated()) {
            return back()->with('error', 'Rencana pengiriman tidak dapat diubah');
        }

        $validated = $request->validate([
            'source_type' => 'required|in:inventory,workshop_output,site_spb,manual',
            'source_id' => 'required_unless:source_type,manual',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|max:50',
            'item_notes' => 'nullable|string',
            'is_consigned' => 'boolean',
            'inventory_id' => 'nullable|exists:inventories,id'
        ]);

        try {
            DB::beginTransaction();

            switch ($validated['source_type']) {
                case 'inventory':
                    $inventory = Inventory::findOrFail($validated['source_id']);

                    $siteSPB = SiteSpb::where('item_name', $inventory->item_name)
                        ->whereNull('delivery_plan_id')
                        ->whereHas('spb', function ($q) {
                            $q->where('status', 'approved');
                        })
                        ->first();

                    if (!$siteSPB) {
                        throw new \Exception('Item tidak memiliki SPB yang disetujui');
                    }

                    if ($inventory->quantity < $validated['quantity']) {
                        throw new \Exception('Stok tidak mencukupi');
                    }

                    $siteSPB->update(['delivery_plan_id' => $plan->id]);
                    break;

                case 'workshop_output':
                    $output = WorkshopOutput::findOrFail($validated['source_id']);
                    if ($output->quantity_produced < $validated['quantity']) {
                        throw new \Exception('Jumlah melebihi hasil produksi');
                    }
                    $output->update(['delivery_plan_id' => $plan->id]);
                    break;

                case 'site_spb':
                    $siteItem = SiteSpb::findOrFail($validated['source_id']);
                    if ($siteItem->quantity < $validated['quantity']) {
                        throw new \Exception('Jumlah melebihi permintaan SPB');
                    }
                    $siteItem->update(['delivery_plan_id' => $plan->id]);
                    break;
            }

            $plan->draftItems()->create($validated);

            DB::commit();
            return redirect()
                ->route('delivery.plans.show', $plan)
                ->with('success', 'Item berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(DeliveryPlan $plan, $itemId)
    {
        if (!$plan->canBeUpdated()) {
            return back()->with('error', 'Rencana pengiriman tidak dapat diubah');
        }

        $item = $plan->draftItems()->findOrFail($itemId);

        try {
            DB::beginTransaction();

            // Reset delivery_plan_id on source
            switch ($item->source_type) {
                case 'inventory':
                    SiteSpb::where('item_name', $item->item_name)
                        ->where('delivery_plan_id', $plan->id)
                        ->update(['delivery_plan_id' => null]);
                    break;

                case 'workshop_output':
                    WorkshopOutput::where('id', $item->source_id)
                        ->update(['delivery_plan_id' => null]);
                    break;

                case 'site_spb':
                    SiteSpb::where('id', $item->source_id)
                        ->update(['delivery_plan_id' => null]);
                    break;
            }

            $item->delete();

            DB::commit();
            return back()->with('success', 'Item berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus item');
        }
    }
}
