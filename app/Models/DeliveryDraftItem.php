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

    public function source()
    {
        return $this->morphTo();
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    // Get source label
    public function getSourceLabel(): string
    {
        return match ($this->source_type) {
            'App\Models\Inventory' => 'Inventori',
            'App\Models\WorkshopOutput' => 'Workshop',
            'App\Models\SiteSpb' => 'SPB Site',
            default => 'Manual'
        };
    }

    // Get source badge class
    public function getSourceBadgeClass(): string
    {
        return match ($this->source_type) {
            'App\Models\Inventory' => 'primary',
            'App\Models\WorkshopOutput' => 'info',
            'App\Models\SiteSpb' => 'warning',
            default => 'secondary'
        };
    }
}
