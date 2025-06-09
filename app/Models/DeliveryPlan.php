<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class DeliveryPlan extends Model
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PACKING = 'packing';
    public const STATUS_READY = 'ready';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'plan_number',
        'destination',
        'planned_date',
        'vehicle_count',
        'vehicle_type',
        'delivery_notes',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'planned_date' => 'date',
        'vehicle_count' => 'integer'
    ];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function packings(): HasMany
    {
        return $this->hasMany(Packing::class);
    }

    public function draftItems(): HasMany
    {
        return $this->hasMany(DeliveryDraftItem::class);
    }

    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(DeliveryNote::class);
    }

    // Status Methods
    public function getStatusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PACKING => 'Packing',
            self::STATUS_READY => 'Siap Kirim',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_PACKING => 'info',
            self::STATUS_READY => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger',
            default => 'secondary'
        };
    }

    // Authorization Methods
    public function canBeUpdated(): bool
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_PACKING
        ]);
    }

    public function canBeCancelled(): bool
    {
        return !in_array($this->status, [
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED
        ]);
    }

    public function canCreateDeliveryNote(): bool
    {
        return $this->status === self::STATUS_READY;
    }

    // Helper Methods
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPacking(): bool
    {
        return $this->status === self::STATUS_PACKING;
    }

    public function isReady(): bool
    {
        return $this->status === self::STATUS_READY;
    }

    public function isOverdue(): bool
    {
        return !$this->isCompleted() &&
            !$this->isCancelled() &&
            $this->planned_date->isPast();
    }

    // Query Scopes
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED
        ]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('planned_date', '<', Carbon::today())
            ->whereNotIn('status', [
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED
            ]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('planned_date', Carbon::today());
    }
}
