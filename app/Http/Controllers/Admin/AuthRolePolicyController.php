<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthRolePolicy;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class AuthRolePolicyController extends Controller
{
    public function index()
    {
        $rolePolicies = AuthRolePolicy::query()
            ->orderByRaw("
                CASE role_name
                    WHEN 'Student' THEN 1
                    WHEN 'Investor' THEN 2
                    WHEN 'Supervisor' THEN 3
                    WHEN 'Manager' THEN 4
                    ELSE 99
                END
            ")
            ->get();

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed role authentication policies index',
            category: 'auth_role_policy_management',
            properties: [
                'roles_count' => $rolePolicies->count(),
            ]
        );

        return view('admin.auth-role-policies.index', compact('rolePolicies'));
    }

    public function show(AuthRolePolicy $rolePolicy)
    {
        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed role authentication policy: ' . $rolePolicy->role_name,
            category: 'auth_role_policy_management',
            subject: $rolePolicy,
            properties: [
                'role_name' => $rolePolicy->role_name,
            ]
        );

        return view('admin.auth-role-policies.show', compact('rolePolicy'));
    }

    public function update(Request $request, AuthRolePolicy $rolePolicy)
    {
        $validated = $request->validate([
            'email_verification_mode' => ['required', 'in:required,optional,disabled'],
            'otp_mode' => ['required', 'in:required,optional,disabled'],
            'trusted_devices_enabled' => ['required', 'boolean'],
            'recovery_codes_enabled' => ['required', 'boolean'],
            'suspicious_login_alerts_enabled' => ['required', 'boolean'],
            'remember_me_enabled' => ['required', 'boolean'],
            'emergency_bypass_enabled' => ['required', 'boolean'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $oldValues = $rolePolicy->toArray();

        $rolePolicy->update([
            'email_verification_mode' => $validated['email_verification_mode'],
            'otp_mode' => $validated['otp_mode'],
            'trusted_devices_enabled' => (bool) $validated['trusted_devices_enabled'],
            'recovery_codes_enabled' => (bool) $validated['recovery_codes_enabled'],
            'suspicious_login_alerts_enabled' => (bool) $validated['suspicious_login_alerts_enabled'],
            'remember_me_enabled' => (bool) $validated['remember_me_enabled'],
            'emergency_bypass_enabled' => (bool) $validated['emergency_bypass_enabled'],
            'notes' => $validated['notes'] ?? null,
        ]);

        AuditLogService::log(
            event: 'updated',
            description: 'Updated role authentication policy: ' . $rolePolicy->role_name,
            category: 'auth_role_policy_management',
            subject: $rolePolicy,
            oldValues: $oldValues,
            newValues: $rolePolicy->fresh()->toArray(),
            properties: [
                'role_name' => $rolePolicy->role_name,
            ]
        );

        return redirect()
            ->route('admin.auth-role-policies.show', $rolePolicy->id)
            ->with('success', 'Role authentication policy updated successfully.');
    }
}