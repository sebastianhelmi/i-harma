<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_note_id',
        'project_id',
        'expedition',
        'delivery_date',
    ];

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function draftItems()
    {
        return $this->hasMany(DirectDeliveryDraftItem::class);
    }
}
