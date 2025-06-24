<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    protected static function booted()
    {
        // When creating a task, set division_id based on assigned user's division
        static::creating(function ($task) {
            if ($task->assigned_to) {
                $assignedUser = User::find($task->assigned_to);
                if ($assignedUser && $assignedUser->role->name === 'Kepala Divisi') {
                    $task->division_id = $assignedUser->division_id;
                }
            }
        });

        // When updating a task, update division_id if assigned_to changes
        static::updating(function ($task) {
            if ($task->isDirty('assigned_to')) {
                $assignedUser = User::find($task->assigned_to);
                if ($assignedUser && $assignedUser->role->name === 'Kepala Divisi') {
                    $task->division_id = $assignedUser->division_id;
                }
            }
        });
    }
    protected $fillable = [
        'name',
        'description',
        'drawing_file',
        'project_id',
        'assigned_to',
        'due_date',
        'status',
        'parent_task_id',
    ];

    public function getDrawingUrl()
    {
        return $this->drawing_file ? asset('storage/' . $this->drawing_file) : null;
    }

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

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
