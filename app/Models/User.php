<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function projects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function requestedSPBs()
    {
        return $this->hasMany(SPB::class, 'requested_by');
    }

    public function approvedSPBs()
    {
        return $this->hasMany(SPB::class, 'approved_by');
    }

    public function createdPOs()
    {
        return $this->hasMany(PO::class, 'created_by');
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'added_by');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'delivered_by');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'generated_by');
    }
}
