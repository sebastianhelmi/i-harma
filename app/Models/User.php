<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'division_id',
        'is_active',
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
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getRoleBadgeClass()
    {
        return match ($this->role) {
            'admin' => 'danger',
            'project_manager' => 'info',
            'head_of_division' => 'primary',
            'purchasing' => 'success',
            default => 'secondary'
        };
    }

    public function getRoleIcon()
    {
        return match ($this->role) {
            'admin' => 'fa-user-shield',
            'project_manager' => 'fa-user-tie',
            'head_of_division' => 'fa-user-cog',
            'purchasing' => 'fa-shopping-cart',
            default => 'fa-user'
        };
    }


    public function getRoleLabel()
    {
        return $this->role->name;
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function isKepalaDivisi(): bool
    {
        return $this->role->name === 'Kepala Divisi';
    }

    /**
     * Get the user's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the user's unread notifications.
     *
     * @return DatabaseNotificationCollection
     */
    public function unreadNotifications()
    {
        return $this->notifications()
            ->whereNull('read_at')
            ->get();
    }
}
