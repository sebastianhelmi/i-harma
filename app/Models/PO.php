<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PO extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'company_name',
        'spb_id',
        'created_by',
        'order_date',
        'total_amount',
        'status',
        'remarks',
    ];

    public function spb()
    {
        return $this->belongsTo(SPB::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function receivedGoods()
    {
        return $this->hasMany(ReceivedGood::class);
    }
    public function poItems()
    {
        return $this->hasMany(POItem::class);
    }
}
