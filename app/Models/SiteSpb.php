<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSpb extends Model
{
    protected $fillable = [
        'spb_id',
        'item_name',
        'unit',
        'quantity',
        'information',
        'document_file',
        'delivery_plan_id'
    ];

    protected $casts = [
        'document_file' => 'array'
    ];

    public function spb()
    {
        return $this->belongsTo(Spb::class);
    }

    public function deliveryPlan()
    {
        return $this->belongsTo(DeliveryPlan::class);
    }

    public function deliveryDraftItems()
    {
        return $this->morphMany(DeliveryDraftItem::class, 'source');
    }
}
