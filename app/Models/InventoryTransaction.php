<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'inventory_id',
        'po_id',
        'delivery_id',
        'quantity',
        'transaction_type',
        'transaction_date',
        'handled_by',
        'remarks'
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    // Transaction types as constants
    const TYPE_IN = 'IN';
    const TYPE_OUT = 'OUT';

    // Relationships
    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function po()
    {
        return $this->belongsTo(Po::class);
    }

    public function deliveryPlan()
    {
        return $this->belongsTo(DeliveryPlan::class, 'delivery_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    // Scopes
    public function scopeIncoming($query)
    {
        return $query->where('transaction_type', self::TYPE_IN);
    }

    public function scopeOutgoing($query)
    {
        return $query->where('transaction_type', self::TYPE_OUT);
    }

    public function scopeForPo($query, $poId)
    {
        return $query->where('po_id', $poId);
    }

    public function scopeForDelivery($query, $deliveryId)
    {
        return $query->where('delivery_id', $deliveryId);
    }

    // Accessor for readable transaction type
    public function getTransactionTypeTextAttribute()
    {
        return $this->transaction_type === self::TYPE_IN ? 'Masuk' : 'Keluar';
    }

    // Helper method to get badge class based on transaction type
    public function getStatusBadgeClass()
    {
        return $this->transaction_type === self::TYPE_IN ? 'success' : 'danger';
    }
}
