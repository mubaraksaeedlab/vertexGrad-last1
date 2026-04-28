<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manager extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'department',
        'last_login',
        'last_activity',
        'login_ip',
        'device',
        'browser',
        'os',
        'profile_image',
    ];

    // Relationship: each manager is linked to a user account
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Permissions for this manager
    public function permissions()
    {
        return $this->hasMany(ManagerPermission::class);
    }

    // Professional helper: get dashboard URL dynamically
    public function getDashboardUrlAttribute(): string
    {
        return route('manager.dashboard');
    }

    // Optional helper: last login formatted
    public function getLastLoginFormattedAttribute(): ?string
    {
        return $this->last_login ? $this->last_login->format('d M Y, H:i') : null;
    }
}