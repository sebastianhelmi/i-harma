<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Packing extends Model
{
    protected $fillable = [
        'delivery_plan_id',
        'packing_number',
        'packing_type',
        'packing_category',
        'packing_dimensions',
        'notes'
    ];

    // Packing Types
    public static function getTypes(): array
    {
        return [
            'box' => 'Box',
            'pallet' => 'Pallet',
            'crate' => 'Crate',
            'barrel' => 'Barrel',
            'bag' => 'Bag',
            'bundle' => 'Bundle',
            'loose' => 'Loose'
        ];
    }

    // Packing Categories
    public static function getCategories(): array
    {
        return [
            'normal' => 'Normal',
            'fragile' => 'Mudah Pecah',
            'dangerous' => 'Berbahaya',
            'liquid' => 'Cairan',
            'heavy' => 'Berat'
        ];
    }

    public function deliveryPlan(): BelongsTo
    {
        return $this->belongsTo(DeliveryPlan::class);
    }

    // Generate unique packing number
    public static function generatePackingNumber(): string
    {
        $prefix = 'PCK';
        $date = now()->format('Ymd');
        $lastPacking = self::where('packing_number', 'like', "{$prefix}{$date}%")
            ->orderBy('packing_number', 'desc')
            ->first();

        if (!$lastPacking) {
            $number = '001';
        } else {
            $lastNumber = substr($lastPacking->packing_number, -3);
            $number = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
        }

        return "{$prefix}{$date}{$number}";
    }
}
