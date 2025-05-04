<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'description'
    ];

    public function pos()
    {
        return $this->hasMany(Po::class);
    }

    public function getRouteKeyName()
    {
        return 'name';
    }
}
