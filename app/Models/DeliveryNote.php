<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_plan_id',
        'delivery_note_number',
        'departure_date',
        'estimated_arrival_date',
        'expedition',
        'vehicle_license_plate',
        'vehicle_type',
    ];

    public function deliveryPlan()
    {
        return $this->belongsTo(DeliveryPlan::class);
    }

    public function deliveryDocument()
    {
        return $this->hasOne(DeliveryDocument::class);
    }

    public function directDelivery()
    {
        return $this->hasOne(DirectDelivery::class);
    }
}
