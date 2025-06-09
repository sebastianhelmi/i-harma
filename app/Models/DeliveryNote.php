<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DeliveryNote extends Model
{
    protected $fillable = [
        'delivery_plan_id',
        'delivery_note_number',
        'departure_date',
        'estimated_arrival_date',
        'expedition',
        'vehicle_type',
        'vehicle_license_plate',
        'created_by'
    ];

    protected $casts = [
        'departure_date' => 'datetime',
        'estimated_arrival_date' => 'datetime'
    ];

    public function deliveryPlan(): BelongsTo
    {
        return $this->belongsTo(DeliveryPlan::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function document(): HasOne
    {
        return $this->hasOne(DeliveryDocument::class);
    }

    // Generate unique delivery note number
    public static function generateNoteNumber(): string
    {
        $prefix = 'SJ';
        $date = now()->format('Ymd');
        $lastNote = self::where('delivery_note_number', 'like', "{$prefix}{$date}%")
            ->orderBy('delivery_note_number', 'desc')
            ->first();

        if (!$lastNote) {
            $number = '001';
        } else {
            $lastNumber = substr($lastNote->delivery_note_number, -3);
            $number = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return "{$prefix}{$date}{$number}";
    }

    public function isOverdue(): bool
    {
        return now()->isAfter($this->estimated_arrival_date);
    }
}
