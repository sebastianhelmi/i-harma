<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_plan_id',
        'packing_type',
        'packing_category',
        'packing_dimensions',
        'packing_number',
    ];

    public function deliveryPlan()
    {
        return $this->belongsTo(DeliveryPlan::class);
    }
}
