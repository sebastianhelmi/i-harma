<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPlan;
use App\Models\DeliveryNote;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryNoteController extends Controller
{
    public function create(DeliveryPlan $plan)
    {
        if (!$plan->canCreateDeliveryNote()) {
            return back()->with('error', 'Tidak dapat membuat surat jalan');
        }

        return view('delivery.notes.create', [
            'plan' => $plan->load(['draftItems', 'packings'])
        ]);
    }

    public function store(Request $request, DeliveryPlan $plan)
    {
        if (!$plan->canCreateDeliveryNote()) {
            return back()->with('error', 'Tidak dapat membuat surat jalan');
        }

        $validated = $request->validate([
            'expedition' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:50',
            'vehicle_license_plate' => 'required|string|max:20',
            'departure_date' => 'required|date',
            'estimated_arrival_date' => 'required|date|after:departure_date',
            'document_files' => 'required|array',
            'document_files.stnk' => 'required|image|max:2048',
            'document_files.license_plate' => 'required|image|max:2048',
            'document_files.vehicle' => 'required|image|max:2048',
            'document_files.driver_license' => 'required|image|max:2048',
            'document_files.driver_id' => 'required|image|max:2048',
            'document_files.loading' => 'required|image|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // Create delivery note
            $note = $plan->deliveryNotes()->create([
                'delivery_note_number' => DeliveryNote::generateNoteNumber(),
                'departure_date' => $validated['departure_date'],
                'estimated_arrival_date' => $validated['estimated_arrival_date'],
                'expedition' => $validated['expedition'],
                'vehicle_type' => $validated['vehicle_type'],
                'vehicle_license_plate' => $validated['vehicle_license_plate'],
                'created_by' => Auth::id()
            ]);

            // Upload documents
            $documents = [
                'vehicle_license_plate' => $validated['vehicle_license_plate'] // Add this line
            ];

            foreach ($validated['document_files'] as $type => $file) {
                $path = $file->store('delivery-documents', 'public');
                switch ($type) {
                    case 'stnk':
                        $documents['stnk_photo'] = $path;
                        break;
                    case 'license_plate':
                        $documents['license_plate_photo'] = $path;
                        break;
                    case 'vehicle':
                        $documents['vehicle_photo'] = $path;
                        break;
                    case 'driver_license':
                        $documents['driver_license_photo'] = $path;
                        break;
                    case 'driver_id':
                        $documents['driver_id_photo'] = $path;
                        break;
                    case 'loading':
                        $documents['loading_process_photo'] = $path;
                        break;
                }
            }

            // Create document records with vehicle_license_plate
            $note->document()->create($documents);

            // Create inventory transactions
            foreach ($plan->draftItems as $item) {
                if ($item->source_type === 'inventory' && $item->inventory_id) {
                    // Create outgoing transaction
                    InventoryTransaction::create([
                        'inventory_id' => $item->inventory_id,
                        'delivery_id' => $plan->id,
                        'quantity' => -$item->quantity,
                        'transaction_type' => 'out',
                        'transaction_date' => now(),
                        'handled_by' => Auth::id(),
                        'remarks' => "Keluar untuk pengiriman {$plan->plan_number}"
                    ]);

                    // Update inventory quantity
                    $item->inventory->decrement('quantity', $item->quantity);
                }
            }

            // Update plan status
            $plan->update(['status' => 'completed']);

            DB::commit();

            return redirect()
                ->route('delivery.plans.show', $plan)
                ->with('success', 'Surat jalan berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}
