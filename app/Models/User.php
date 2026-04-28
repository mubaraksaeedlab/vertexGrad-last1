<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserAuthPolicyOverride;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'status',
        'gender',
        'city',
        'state',
        'profile_image',
        'status',

        // tracking fields
        'last_login',
        'last_activity',
        'login_ip',
        'device',
        'browser',
        'os',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'last_activity' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Main Role/Permission Relations
    |--------------------------------------------------------------------------
    */

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user')->withTimestamps();
    }

    public function hasRole(string $role): bool
    {
        if ($this->role === $role) {
            return true;
        }

        return $this->roles()->where('name', $role)->exists()
            || $this->roles()->where('slug', strtolower($role))->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        if (in_array($this->role, $roles, true)) {
            return true;
        }

        return $this->roles()
            ->where(function ($query) use ($roles) {
                $query->whereIn('name', $roles)
                    ->orWhereIn('slug', array_map('strtolower', $roles));
            })
            ->exists();
    }

    public function hasPermission(string $permission): bool
    {
        // 1) Direct user permissions
        if ($this->permissions()->where('slug', $permission)->exists()) {
            return true;
        }

        // 2) Role-based permissions
        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('slug', $permission);
        })->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | Role-based profile relations
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function supervisor()
    {
        return $this->hasOne(Supervisor::class);
    }
    public function authPolicyOverride()
    {
        return $this->hasOne(UserAuthPolicyOverride::class);
    }
    public function manager()
    {
        return $this->hasOne(Manager::class);
    }

    public function investor()
    {
        return $this->hasOne(Investor::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Projects
    |--------------------------------------------------------------------------
    */

    public function projects()
    {
        return $this->hasMany(Project::class, 'student_id');
    }

    public function projectReviews()
    {
        return $this->hasMany(\App\Models\ProjectReview::class, 'supervisor_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Investments
    |--------------------------------------------------------------------------
    */

    public function investments()
    {
        return $this->belongsToMany(
            Project::class,
            'project_investments',
            'investor_id',
            'project_id',
            'id',
            'project_id'
        )->withPivot('status', 'amount', 'message')->withTimestamps();
    }

    public function investedProjects()
    {
        return $this->belongsToMany(Project::class, 'investor_project', 'investor_id', 'project_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Auto-create related role models
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::saved(function ($user) {
            if (! $user->wasRecentlyCreated && ! $user->wasChanged('role')) {
                return;
            }

            $role = $user->role;

            $roleModels = [
                'Manager'    => \App\Models\Manager::class,
                'Investor'   => \App\Models\Investor::class,
                'Student'    => \App\Models\Student::class,
                'Supervisor' => \App\Models\Supervisor::class,
            ];

            foreach ($roleModels as $roleName => $modelClass) {
                if ($role === $roleName) {
                    $modelClass::firstOrCreate(['user_id' => $user->id]);
                } else {
                    $modelClass::where('user_id', $user->id)->delete();
                }
            }
        });
    }
}
