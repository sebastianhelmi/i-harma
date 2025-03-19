<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_id',
        'spb_id',
        'workshop_spb_id',
        'site_spb_id',
        'item_name',
        'unit',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function po()
    {
        return $this->belongsTo(PO::class);
    }

    public function spb()
    {
        return $this->belongsTo(SPB::class);
    }

    public function workshopSPB()
    {
        return $this->belongsTo(WorkshopSPB::class);
    }

    public function siteSPB()
    {
        return $this->belongsTo(SiteSPB::class);
    }
}
