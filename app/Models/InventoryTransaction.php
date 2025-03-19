<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'po_id',
        'delivery_id',
        'quantity',
        'transaction_type',
        'transaction_date',
        'handled_by',
        'remarks',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function po()
    {
        return $this->belongsTo(PO::class);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
