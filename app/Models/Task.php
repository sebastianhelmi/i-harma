<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'project_id',
        'assigned_to',
        'due_date',
        'status',
        'parent_task_id',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }
    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }
    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
        };
    }
    public function getStatusLabel()
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
        };
    }
    public function getStatusColor()
    {
        return match ($this->status) {
            'pending' => 'text-warning',
            'in_progress' => 'text-info',
            'completed' => 'text-success',
        };
    }
    public function getStatusTextColor()
    {
        return match ($this->status) {
            'pending' => 'text-warning',
            'in_progress' => 'text-info',
            'completed' => 'text-success',
        };
    }
    public function getStatusIcon()
    {
        return match ($this->status) {
            'pending' => 'fa-clock',
            'in_progress' => 'fa-spinner',
            'completed' => 'fa-check',
        };
    }
    public function getStatusIconColor()
    {
        return match ($this->status) {
            'pending' => 'text-warning',
            'in_progress' => 'text-info',
            'completed' => 'text-success',
        };
    }

    public function spb()
{
    return $this->hasOne(Spb::class);
}

public function workshopOutputs()
{
    return $this->hasMany(WorkshopOutput::class);
}

}
