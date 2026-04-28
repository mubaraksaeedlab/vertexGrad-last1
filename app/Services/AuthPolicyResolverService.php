<?php

namespace App\Services;

use App\Models\AuthRolePolicy;
use App\Models\User;
use App\Models\UserAuthPolicyOverride;

class AuthPolicyResolverService
{
    public static function resolveForUser(User $user): array
    {
        $roleName = $user->role;

        $rolePolicy = AuthRolePolicy::query()
            ->where('role_name', $roleName)
            ->first();

        $defaultPolicy = [
            'role_name' => $roleName,
            'email_verification_mode' => 'required',
            'otp_mode' => in_array($roleName, ['Investor', 'Supervisor', 'Manager'], true) ? 'required' : 'optional',
            'trusted_devices_enabled' => true,
            'recovery_codes_enabled' => true,
            'suspicious_login_alerts_enabled' => true,
            'remember_me_enabled' => true,
            'emergency_bypass_enabled' => false,
            'notes' => null,
            'source' => 'system_default',
        ];

        if ($rolePolicy) {
            $defaultPolicy = [
                'role_name' => $rolePolicy->role_name,
                'email_verification_mode' => $rolePolicy->email_verification_mode,
                'otp_mode' => $rolePolicy->otp_mode,
                'trusted_devices_enabled' => (bool) $rolePolicy->trusted_devices_enabled,
                'recovery_codes_enabled' => (bool) $rolePolicy->recovery_codes_enabled,
                'suspicious_login_alerts_enabled' => (bool) $rolePolicy->suspicious_login_alerts_enabled,
                'remember_me_enabled' => (bool) $rolePolicy->remember_me_enabled,
                'emergency_bypass_enabled' => (bool) $rolePolicy->emergency_bypass_enabled,
                'notes' => $rolePolicy->notes,
                'source' => 'role_policy',
            ];
        }

        $override = $user->relationLoaded('authPolicyOverride')
            ? $user->authPolicyOverride
            : $user->authPolicyOverride()->first();

        if (! $override || $override->use_role_defaults) {
            return $defaultPolicy;
        }

        return [
            'role_name' => $roleName,
            'email_verification_mode' => $override->email_verification_mode ?? $defaultPolicy['email_verification_mode'],
            'otp_mode' => $override->otp_mode ?? $defaultPolicy['otp_mode'],
            'trusted_devices_enabled' => ! is_null($override->trusted_devices_enabled)
                ? (bool) $override->trusted_devices_enabled
                : $defaultPolicy['trusted_devices_enabled'],
            'recovery_codes_enabled' => ! is_null($override->recovery_codes_enabled)
                ? (bool) $override->recovery_codes_enabled
                : $defaultPolicy['recovery_codes_enabled'],
            'suspicious_login_alerts_enabled' => ! is_null($override->suspicious_login_alerts_enabled)
                ? (bool) $override->suspicious_login_alerts_enabled
                : $defaultPolicy['suspicious_login_alerts_enabled'],
            'remember_me_enabled' => ! is_null($override->remember_me_enabled)
                ? (bool) $override->remember_me_enabled
                : $defaultPolicy['remember_me_enabled'],
            'emergency_bypass_enabled' => ! is_null($override->emergency_bypass_enabled)
                ? (bool) $override->emergency_bypass_enabled
                : $defaultPolicy['emergency_bypass_enabled'],
            'notes' => $override->notes,
            'source' => 'user_override',
        ];
    }
}