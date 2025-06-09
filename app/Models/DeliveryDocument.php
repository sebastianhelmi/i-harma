<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryDocument extends Model
{
    protected $fillable = [
        'delivery_note_id',
        'vehicle_license_plate',
        'stnk_photo',
        'license_plate_photo',
        'vehicle_photo',
        'driver_license_photo',
        'driver_id_photo',
        'loading_process_photo'
    ];

    /**
     * Get the delivery note associated with the document
     */
    public function deliveryNote(): BelongsTo
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    /**
     * Get full URL for a photo attribute
     */
    public function getPhotoUrl(string $photoAttribute): string
    {
        if (empty($this->$photoAttribute)) {
            return '';
        }

        return asset('storage/' . $this->$photoAttribute);
    }

    /**
     * Get all document photo URLs as array
     */
    public function getAllPhotoUrls(): array
    {
        $photos = [
            'stnk' => $this->getPhotoUrl('stnk_photo'),
            'license_plate' => $this->getPhotoUrl('license_plate_photo'),
            'vehicle' => $this->getPhotoUrl('vehicle_photo'),
            'driver_license' => $this->getPhotoUrl('driver_license_photo'),
            'driver_id' => $this->getPhotoUrl('driver_id_photo'),
            'loading_process' => $this->getPhotoUrl('loading_process_photo')
        ];

        return array_filter($photos);
    }

    /**
     * Get document labels
     */
    public static function getDocumentLabels(): array
    {
        return [
            'stnk_photo' => 'STNK',
            'license_plate_photo' => 'Plat Nomor',
            'vehicle_photo' => 'Kendaraan',
            'driver_license_photo' => 'SIM Driver',
            'driver_id_photo' => 'KTP Driver',
            'loading_process_photo' => 'Proses Muat'
        ];
    }

    /**
     * Check if all required photos are present
     */
    public function hasAllRequiredPhotos(): bool
    {
        $requiredPhotos = [
            'stnk_photo',
            'license_plate_photo',
            'vehicle_photo',
            'driver_license_photo',
            'driver_id_photo',
            'loading_process_photo'
        ];

        foreach ($requiredPhotos as $photo) {
            if (empty($this->$photo)) {
                return false;
            }
        }

        return true;
    }
}
