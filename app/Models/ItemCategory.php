<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'item_category_id');
    }
}
