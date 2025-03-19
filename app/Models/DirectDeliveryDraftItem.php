<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectDeliveryDraftItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'direct_delivery_id',
        'item_name',
        'unit',
        'quantity',
    ];

    public function directDelivery()
    {
        return $this->belongsTo(DirectDelivery::class);
    }
}
