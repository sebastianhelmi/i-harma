<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_note_id',
        'vehicle_license_plate',
        'stnk_photo',
        'license_plate_photo',
        'vehicle_photo',
        'driver_license_photo',
        'driver_id_photo',
        'loading_process_photo',
    ];

    public function deliveryNote()
    {
        return $this->belongsTo(DeliveryNote::class);
    }
}
