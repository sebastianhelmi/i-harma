<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function kepalaDivisi()
    {
        return $this->hasOne(User::class)->whereHas('role', function($query) {
            $query->where('name', 'Kepala Divisi');
        });
    }
}
