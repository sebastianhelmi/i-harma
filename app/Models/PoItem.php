<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoItem extends Model
{
    protected $fillable = [
        'po_id',
        'spb_id',
        'site_spb_id',
        'workshop_spb_id',
        'item_name',
        'unit',
        'quantity',
        'unit_price',
        'total_price'
    ];

    public function po()
    {
        return $this->belongsTo(Po::class);
    }
}
