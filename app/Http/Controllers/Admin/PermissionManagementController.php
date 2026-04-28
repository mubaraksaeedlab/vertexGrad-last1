<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class PermissionManagementController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed permissions management index',
            category: 'permission_management',
            properties: [
                'users_count' => $users->count(),
            ]
        );

        return view('admin.permissions.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['roles.permissions', 'permissions']);

        $permissions = Permission::orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy('group');

        $directPermissionIds = $user->permissions->pluck('id')->toArray();

        $rolePermissionIds = $user->roles
            ->flatMap(fn ($role) => $role->permissions)
            ->pluck('id')
            ->unique()
            ->toArray();

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed permission details for user: ' . ($user->name ?? $user->username),
            category: 'permission_management',
            subject: $user,
            properties: [
                'user_id' => $user->id,
                'user_name' => $user->name ?? $user->username,
                'user_email' => $user->email,
                'roles' => $user->roles->pluck('name')->values()->toArray(),
                'direct_permissions_count' => count($directPermissionIds),
                'role_permissions_count' => count($rolePermissionIds),
            ]
        );

        return view('admin.permissions.show', compact(
            'user',
            'permissions',
            'directPermissionIds',
            'rolePermissionIds'
        ));
    }

    public function sync(Request $request, User $user)
    {
        $user->load(['permissions', 'roles.permissions']);

        $validated = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['integer', 'exists:permissions,id'],
        ]);

        $permissionIds = $validated['permissions'] ?? [];

        $oldDirectPermissions = $user->permissions
            ->map(fn ($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
                'slug' => $permission->slug,
                'group' => $permission->group,
            ])
            ->values()
            ->toArray();

        $oldDirectPermissionIds = $user->permissions->pluck('id')->sort()->values()->toArray();

        $user->permissions()->sync($permissionIds);

        $user->load(['permissions', 'roles.permissions']);

        $newDirectPermissions = $user->permissions
            ->map(fn ($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
                'slug' => $permission->slug,
                'group' => $permission->group,
            ])
            ->values()
            ->toArray();

        $newDirectPermissionIds = $user->permissions->pluck('id')->sort()->values()->toArray();

        $addedPermissionIds = array_values(array_diff($newDirectPermissionIds, $oldDirectPermissionIds));
        $removedPermissionIds = array_values(array_diff($oldDirectPermissionIds, $newDirectPermissionIds));

        $addedPermissions = $user->permissions
            ->whereIn('id', $addedPermissionIds)
            ->map(fn ($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
                'slug' => $permission->slug,
                'group' => $permission->group,
            ])
            ->values()
            ->toArray();

        $removedPermissions = collect($oldDirectPermissions)
            ->whereIn('id', $removedPermissionIds)
            ->values()
            ->toArray();

        AuditLogService::log(
            event: 'permissions_synced',
            description: 'Updated direct permissions for user: ' . ($user->name ?? $user->username),
            category: 'permission_management',
            subject: $user,
            oldValues: [
                'direct_permissions' => $oldDirectPermissions,
                'direct_permission_ids' => $oldDirectPermissionIds,
            ],
            newValues: [
                'direct_permissions' => $newDirectPermissions,
                'direct_permission_ids' => $newDirectPermissionIds,
            ],
            properties: [
                'user_id' => $user->id,
                'user_name' => $user->name ?? $user->username,
                'user_email' => $user->email,
                'roles' => $user->roles->pluck('name')->values()->toArray(),
                'added_permissions' => $addedPermissions,
                'removed_permissions' => $removedPermissions,
                'added_permission_ids' => $addedPermissionIds,
                'removed_permission_ids' => $removedPermissionIds,
            ]
        );

        return redirect()
            ->route('admin.permissions.show', $user->id)
            ->with('success', 'Permissions updated successfully.');
    }
}