<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAuthPolicyOverride extends Model
{
    protected $fillable = [
        'user_id',
        'use_role_defaults',
        'email_verification_mode',
        'otp_mode',
        'trusted_devices_enabled',
        'recovery_codes_enabled',
        'suspicious_login_alerts_enabled',
        'remember_me_enabled',
        'emergency_bypass_enabled',
        'notes',
    ];

    protected $casts = [
        'use_role_defaults' => 'boolean',
        'trusted_devices_enabled' => 'boolean',
        'recovery_codes_enabled' => 'boolean',
        'suspicious_login_alerts_enabled' => 'boolean',
        'remember_me_enabled' => 'boolean',
        'emergency_bypass_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}