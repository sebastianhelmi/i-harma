<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedGood extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_id',
        'inventory_id',
        'quantity_received',
        'received_date',
        'received_by',
        'status',
        'remarks',
    ];

    public function po()
    {
        return $this->belongsTo(PO::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
