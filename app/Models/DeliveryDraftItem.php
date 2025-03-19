<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDraftItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_plan_id',
        'is_consigned',
        'item_name',
        'quantity',
        'unit',
        'item_notes',
    ];

    public function deliveryPlan()
    {
        return $this->belongsTo(DeliveryPlan::class);
    }
}
