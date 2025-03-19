<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'planned_delivery_date',
        'destination_site',
        'vehicle_count',
        'delivery_notes',
    ];

    public function packings()
    {
        return $this->hasMany(Packing::class);
    }

    public function draftItems()
    {
        return $this->hasMany(DeliveryDraftItem::class);
    }

    public function deliveryNote()
    {
        return $this->hasOne(DeliveryNote::class);
    }
}
