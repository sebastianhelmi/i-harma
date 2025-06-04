<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkshopOutput extends Model
{
    protected $fillable = [
        'task_id',
        'spb_id',
        'workshop_spb_id',
        'inventory_id',
        'quantity_produced',
        'status',
        'need_delivery',
        'notes',
        'created_by',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'need_delivery' => 'boolean'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function spb()
    {
        return $this->belongsTo(Spb::class);
    }

    public function workshopSpb()
    {
        return $this->belongsTo(WorkshopSpb::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
