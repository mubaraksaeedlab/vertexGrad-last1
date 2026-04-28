<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthRolePolicy;
use App\Models\User;
use App\Models\UserAuthPolicyOverride;
use App\Services\AuditLogService;
use App\Services\AuthPolicyResolverService;
use Illuminate\Http\Request;

class AuthPolicyManagementController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->with('authPolicyOverride')
            ->orderBy('name')
            ->get();

        $users = $users->map(function ($user) {
            $user->effective_auth_policy = AuthPolicyResolverService::resolveForUser($user);
            return $user;
        });

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed authentication policy management index',
            category: 'auth_policy_management',
            properties: [
                'users_count' => $users->count(),
            ]
        );

        return view('admin.auth-policies.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('authPolicyOverride');

        $rolePolicy = AuthRolePolicy::query()
            ->where('role_name', $user->role)
            ->first();

        $effectivePolicy = AuthPolicyResolverService::resolveForUser($user);

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed authentication policy details for user: ' . ($user->name ?? $user->username),
            category: 'auth_policy_management',
            subject: $user,
            properties: [
                'user_id' => $user->id,
                'user_name' => $user->name ?? $user->username,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'policy_source' => $effectivePolicy['source'] ?? 'unknown',
            ]
        );

        return view('admin.auth-policies.show', compact(
            'user',
            'rolePolicy',
            'effectivePolicy'
        ));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'use_role_defaults' => ['required', 'boolean'],
            'email_verification_mode' => ['nullable', 'in:required,optional,disabled'],
            'otp_mode' => ['nullable', 'in:required,optional,disabled'],
            'trusted_devices_enabled' => ['nullable', 'boolean'],
            'recovery_codes_enabled' => ['nullable', 'boolean'],
            'suspicious_login_alerts_enabled' => ['nullable', 'boolean'],
            'remember_me_enabled' => ['nullable', 'boolean'],
            'emergency_bypass_enabled' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $oldOverride = $user->authPolicyOverride ? $user->authPolicyOverride->toArray() : null;

        $override = UserAuthPolicyOverride::firstOrNew([
            'user_id' => $user->id,
        ]);

        $override->use_role_defaults = (bool) $validated['use_role_defaults'];

        if ($override->use_role_defaults) {
            $override->email_verification_mode = null;
            $override->otp_mode = null;
            $override->trusted_devices_enabled = null;
            $override->recovery_codes_enabled = null;
            $override->suspicious_login_alerts_enabled = null;
            $override->remember_me_enabled = null;
            $override->emergency_bypass_enabled = null;
            $override->notes = $validated['notes'] ?? null;
        } else {
            $override->email_verification_mode = $validated['email_verification_mode'] ?? null;
            $override->otp_mode = $validated['otp_mode'] ?? null;
            $override->trusted_devices_enabled = array_key_exists('trusted_devices_enabled', $validated)
                ? (bool) $validated['trusted_devices_enabled']
                : null;
            $override->recovery_codes_enabled = array_key_exists('recovery_codes_enabled', $validated)
                ? (bool) $validated['recovery_codes_enabled']
                : null;
            $override->suspicious_login_alerts_enabled = array_key_exists('suspicious_login_alerts_enabled', $validated)
                ? (bool) $validated['suspicious_login_alerts_enabled']
                : null;
            $override->remember_me_enabled = array_key_exists('remember_me_enabled', $validated)
                ? (bool) $validated['remember_me_enabled']
                : null;
            $override->emergency_bypass_enabled = array_key_exists('emergency_bypass_enabled', $validated)
                ? (bool) $validated['emergency_bypass_enabled']
                : null;
            $override->notes = $validated['notes'] ?? null;
        }

        $override->save();

        $user->load('authPolicyOverride');
        $newOverride = $user->authPolicyOverride ? $user->authPolicyOverride->toArray() : null;
        $effectivePolicy = AuthPolicyResolverService::resolveForUser($user);

        AuditLogService::log(
            event: 'auth_policy_updated',
            description: 'Updated authentication policy for user: ' . ($user->name ?? $user->username),
            category: 'auth_policy_management',
            subject: $user,
            oldValues: [
                'auth_policy_override' => $oldOverride,
            ],
            newValues: [
                'auth_policy_override' => $newOverride,
                'effective_policy' => $effectivePolicy,
            ],
            properties: [
                'user_id' => $user->id,
                'user_name' => $user->name ?? $user->username,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'policy_source' => $effectivePolicy['source'] ?? 'unknown',
            ]
        );

        return redirect()
            ->route('admin.auth-policies.show', $user->id)
            ->with('success', 'Authentication policy updated successfully.');
    }
}