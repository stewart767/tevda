<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role_id',
        'is_active',
        'avatar_path',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function notificationsCustom()
    {
        return $this->hasMany(NotificationCustom::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotificationsCustom()
    {
        return $this->hasMany(NotificationCustom::class)->where('is_read', false)->orderBy('created_at', 'desc');
    }

    public function hasRole(string|array $roles): bool
    {
        if (!$this->role) {
            return false;
        }

        if (is_string($roles)) {
            return $this->role->slug === $roles || $this->role->slug === 'super-admin';
        }

        return in_array($this->role->slug, $roles) || $this->role->slug === 'super-admin';
    }

    public function hasPermission(string $permission): bool
    {
        if (!$this->role) {
            return false;
        }

        if ($this->role->slug === 'super-admin') {
            return true;
        }

        $permissions = $this->role->permissions ?? [];
        return in_array($permission, $permissions) || in_array('*', $permissions);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->slug === 'super-admin';
    }

    public function isStaff(): bool
    {
        if (!$this->role) return false;
        return $this->role->slug !== 'member';
    }

    public function isMember(): bool
    {
        return $this->role && $this->role->slug === 'member';
    }
}
