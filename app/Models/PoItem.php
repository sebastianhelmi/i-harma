<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PoItem extends Model
{
    protected $fillable = [
        'po_id',
        'item_id',
        'quantity',
        'unit_price',
        'total_price',
        'description'
    ];

    public function po()
    {
        return $this->belongsTo(Po::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
