<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Po extends Model
{
    protected $fillable = [
        'po_number',
        'spb_id',
        'created_by',
        'supplier_id',
        'company_name',
        'order_date',
        'estimated_usage_date',
        'total_amount',
        'status',
        'remarks'
    ];

    protected $casts = [
        'order_date' => 'date',
        'estimated_usage_date' => 'date'
    ];

    public function spb()
    {
        return $this->belongsTo(Spb::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(PoItem::class);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'warning',
            'completed' => 'success',
            'cancelled' => 'danger',
        };
    }

    public static function generatePoNumber()
    {
        $prefix = 'PO-' . date('Ym-');
        $lastNumber = static::where('po_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastNumber ?
            intval(substr($lastNumber->po_number, -3)) + 1 :
            1;

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
