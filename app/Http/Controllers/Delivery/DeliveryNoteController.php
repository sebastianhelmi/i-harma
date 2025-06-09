<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPlan;
use App\Models\DeliveryNote;
use App\Models\InventoryTransaction;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;

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

            // Upload documents with proper field mapping
            $documents = [
                'vehicle_license_plate' => $validated['vehicle_license_plate']
            ];

            // Map file types to database field names
            $fileMapping = [
                'stnk' => 'stnk_photo',
                'license_plate' => 'license_plate_photo',
                'vehicle' => 'vehicle_photo',
                'driver_license' => 'driver_license_photo',
                'driver_id' => 'driver_id_photo',
                'loading' => 'loading_process_photo'  // Fix the field name mapping
            ];

            foreach ($validated['document_files'] as $type => $file) {
                $path = $file->store('delivery-documents', 'public');
                $fieldName = $fileMapping[$type];
                $documents[$fieldName] = $path;
            }

            // Create document records with all required fields
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


            // Update plan status to shipping instead of completed
            $plan->update([
                'status' => DeliveryPlan::STATUS_SHIPPING,
                'updated_by' => Auth::id()
            ]);


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

    public function print(DeliveryNote $note)
    {
        $note->load([
            'deliveryPlan.draftItems',
            'deliveryPlan.packings',
            'document',
            'creator'
        ]);

        $pdf = FacadePdf::loadView('delivery.notes.print', compact('note'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream("surat-jalan-{$note->delivery_note_number}.pdf");
    }
}
