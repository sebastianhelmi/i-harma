<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'manager_id',
        'files'
    ];

    protected $casts = [
        'files' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'ongoing' => 'primary',
            'completed' => 'success',
        };
    }
}
