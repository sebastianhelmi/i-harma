<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPB extends Model
{
    use HasFactory;

    protected $fillable = [
        'spb_number',
        'spb_date',
        'project_id',
        'requested_by',
        'item_category_id',
        'category_item',
        'status',
        'remarks',
        'approved_at',
        'approved_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function pos()
    {
        return $this->hasMany(PO::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function workshopDetails()
    {
        return $this->hasOne(WorkshopSPB::class);
    }

    public function siteDetails()
    {
        return $this->hasOne(SiteSPB::class);
    }

    public function details()
    {
        return $this->category_item === 'workshop'
            ? $this->workshopDetails()
            : $this->siteDetails();
    }
}
