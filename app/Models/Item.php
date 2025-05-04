<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'description',
        'category_id',
        'supplier_id',
        'stock',
        'min_stock',
        'max_stock',
        'reorder_level',
        'reorder_quantity'
    ];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function poItems()
    {
        return $this->hasMany(PoItem::class);
    }
}
