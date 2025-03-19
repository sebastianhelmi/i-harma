<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'budget',
        'status',
        'manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function spbs()
    {
        return $this->hasMany(SPB::class);
    }

    public function directDeliveries()
    {
        return $this->hasMany(DirectDelivery::class);
    }


    protected $casts = [
        'files' => 'array',
    ];
}
