<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'item_name',
        'category',
        'quantity',
        'initial_stock',
        'item_category_id',
        'unit',
        'weight',
        'unit_price',
        'added_by',
    ];

    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function siteSpbs()
    {
        return $this->hasMany(SiteSpb::class, 'item_name', 'item_name');
    }

    public function deliveryDraftItems()
    {
        return $this->morphMany(DeliveryDraftItem::class, 'source');
    }
}
