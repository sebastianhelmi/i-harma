<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopSpb extends Model
{
    protected $fillable = [
        'spb_id',
        'explanation_items',
        'unit',
        'quantity'
    ];

    public function spb()
    {
        return $this->belongsTo(Spb::class);
    }
}
