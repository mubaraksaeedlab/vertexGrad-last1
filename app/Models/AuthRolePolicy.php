<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthRolePolicy extends Model
{
    protected $fillable = [
        'role_name',
        'email_verification_mode',
        'otp_mode',
        'trusted_devices_enabled',
        'recovery_codes_enabled',
        'suspicious_login_alerts_enabled',
        'remember_me_enabled',
        'emergency_bypass_enabled',
        'notes',
    ];
}