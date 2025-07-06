<?php

namespace App\Models;

use App\Notifications\NewDivisionReportNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DivisionReport extends Model
{

    protected $fillable = [
        'project_id',
        'division_id',
        'report_number',
        'report_date',
        'report_type',
        'progress_summary',
        'challenges',
        'next_plan',
        'attachments',
        'related_tasks',
        'task_progress',
        'created_by',
        'acknowledged_by',
        'acknowledged_at'
    ];


    protected $casts = [
        'report_date' => 'date',
        'attachments' => 'array',
        'related_tasks' => 'array',
        'task_progress' => 'array',
        'acknowledged_at' => 'datetime'
    ];

    protected static function booted()
    {
        static::created(function ($report) {
            // Get Project Manager
            $projectManager = $report->project->manager;

            // Send notification
            $projectManager->notify(new NewDivisionReportNotification($report));
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function acknowledger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public static function generateReportNumber(): string
    {
        $prefix = 'RPT';
        $date = now()->format('Ymd');
        $lastReport = self::where('report_number', 'like', "{$prefix}{$date}%")
            ->orderBy('report_number', 'desc')
            ->first();

        $number = $lastReport ?
            (int)substr($lastReport->report_number, -3) + 1 :
            1;

        return sprintf("%s%s%03d", $prefix, $date, $number);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'report_tasks')
            ->withPivot('progress_notes')
            ->withTimestamps();
    }

    // Tambahkan method helper
    public function addTaskProgress($taskId, $progress)
    {
        $taskProgress = $this->task_progress ?? [];
        $taskProgress[$taskId] = $progress;
        $this->task_progress = $taskProgress;
        $this->save();
    }

    public function needsAcknowledgment(): bool
    {
        return is_null($this->acknowledged_by) && is_null($this->acknowledged_at);
    }
}
