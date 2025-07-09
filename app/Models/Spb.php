<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spb extends Model
{
    protected $fillable = [
        'spb_number',
        'spb_date',
        'project_id',
        'requested_by',
        'task_id',
        'item_category_id',
        'category_entry',
        'status',
        'status_po',
        'remarks',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'spb_date' => 'date',
        'estimasi_pakai' => 'date',
        'approved_at' => 'datetime'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function po()
    {
        return $this->hasOne(Po::class);
    }


    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function siteItems()
    {
        return $this->hasMany(SiteSpb::class);
    }

    public function workshopItems()
    {
        return $this->hasMany(WorkshopSpb::class);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
        };
    }

    public static function generateSpbNumber()
    {
        $prefix = 'SPB-' . date('Ym-');
        $lastNumber = static::where('spb_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastNumber ?
            intval(substr($lastNumber->spb_number, -3)) + 1 :
            1;

        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function division()
    {
        return $this->task->division();
    }

    public function getDivisionNameAttribute()
    {
        return $this->task?->division?->name ?? 'N/A';
    }
}
