<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPlan;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeliveryConfirmationController extends Controller
{
    public function showConfirmationForm(DeliveryPlan $plan)
    {
        if ($plan->status !== 'delivering') {
            return redirect()->route('delivery.plans.show', $plan)->with('error', "Pengiriman belum dalam status 'Dalam Perjalanan'.");
        }

        $plan->load('draftItems.inventory');

        return view('delivery.plans.confirm', compact('plan'));
    }

    public function confirmDelivery(Request $request, DeliveryPlan $plan)
    {
        if ($plan->status !== 'delivering') {
            return back()->with('error', "Pengiriman belum dalam status 'Dalam Perjalanan'.");
        }

        $validated = $request->validate([
            'proof_of_delivery' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Upload proof of delivery
            $filePath = $request->file('proof_of_delivery')->store('proofs', 'public');

            // Update delivery plan status and proof path
            $plan->update([
                'status' => 'completed',
                'proof_of_delivery_path' => $filePath,
                'delivery_notes' => $validated['notes'] ?? $plan->delivery_notes,
                'updated_by' => Auth::id(),
            ]);

            // Decrement inventory and create transactions
            $plan->load('draftItems.inventory');

            foreach ($plan->draftItems as $item) {
                $inventory = $item->inventory;

                if (!$inventory || $inventory->quantity < $item->quantity) {
                    throw new \Exception("Stok tidak mencukupi untuk item: {$item->item_name}");
                }

                $inventory->decrement('quantity', $item->quantity);

                InventoryTransaction::create([
                    'inventory_id' => $inventory->id,
                    'delivery_id' => $plan->id,
                    'quantity' => -abs($item->quantity),
                    'transaction_type' => 'OUT',
                    'transaction_date' => now(),
                    'handled_by' => Auth::id(),
                    'remarks' => "Pengiriman selesai untuk rencana #{$plan->plan_number}",
                    'stock_after_transaction' => $inventory->quantity,
                ]);
            }

            DB::commit();

            return redirect()->route('delivery.plans.show', $plan)->with('success', 'Pengiriman berhasil dikonfirmasi dan stok telah diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            // If file was uploaded, delete it
            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return back()->with('error', 'Terjadi kesalahan saat konfirmasi pengiriman: ' . $e->getMessage());
        }
    }
}
