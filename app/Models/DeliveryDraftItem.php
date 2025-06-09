<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryDraftItem extends Model
{
    protected $fillable = [
        'delivery_plan_id',
        'item_name',
        'quantity',
        'unit',
        'is_consigned',
        'item_notes',
        'source_type',
        'source_id',
        'inventory_id'
    ];

    protected $casts = [
        'is_consigned' => 'boolean',
        'quantity' => 'integer'
    ];

    public function deliveryPlan(): BelongsTo
    {
        return $this->belongsTo(DeliveryPlan::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function workshopOutput(): BelongsTo
    {
        return $this->belongsTo(WorkshopOutput::class, 'source_id');
    }

    public function siteSpb(): BelongsTo
    {
        return $this->belongsTo(SiteSpb::class, 'source_id');
    }

    // Get source label
    public function getSourceLabel(): string
    {
        return match ($this->source_type) {
            'inventory' => 'Inventori',
            'workshop_output' => 'Workshop',
            'site_spb' => 'SPB Site',
            default => 'Manual'
        };
    }

    // Get source badge class
    public function getSourceBadgeClass(): string
    {
        return match ($this->source_type) {
            'inventory' => 'primary',
            'workshop_output' => 'info',
            'site_spb' => 'warning',
            default => 'secondary'
        };
    }
}
