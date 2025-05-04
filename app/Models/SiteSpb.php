<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSpb extends Model
{
    protected $fillable = [
        'spb_id',
        'item_name',
        'unit',
        'quantity',
        'information',
        'document_file'
    ];

    protected $casts = [
        'document_file' => 'array'
    ];

    public function spb()
    {
        return $this->belongsTo(Spb::class);
    }
}
