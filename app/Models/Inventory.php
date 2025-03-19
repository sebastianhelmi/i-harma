<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'category',
        'quantity',
        'unit',
        'unit_price',
        'added_by',
    ];

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function deliveries()
    {
        return $this->hasMany(DeliveryPlan::class);
    }
    public function receivedGoods()
    {
        return $this->hasMany(ReceivedGood::class);
    }
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
}
