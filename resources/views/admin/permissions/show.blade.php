@extends('layouts.app')

@section('title', __('backend.permissions_show.title'))

@section('content')
<style>
    .permissions-page .hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #3b82f6 100%);
        border-radius: 24px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .permissions-page .hero-card::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -40px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .permissions-page .hero-card::after {
        content: "";
        position: absolute;
        bottom: -60px;
        left: -50px;
        width: 220px;
        height: 220px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .permissions-page .hero-content {
        position: relative;
        z-index: 2;
    }

    .permissions-page .hero-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #fff;
    }

    .permissions-page .hero-text {
        font-size: 14px;
        opacity: .92;
        margin-bottom: 0;
        max-width: 820px;
        line-height: 1.8;
    }

    .permissions-page .section-card {
        background: #fff;
        border-radius: 22px;
        border: 1px solid #eef2f7;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .permissions-page .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .permissions-page .section-header h4,
    .permissions-page .section-header h5 {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
    }

    .permissions-page .section-subtext {
        margin-top: 6px;
        color: #64748b;
        font-size: 13px;
    }

    .permissions-page .section-body {
        padding: 24px;
    }

    .permissions-page .info-box {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        height: 100%;
    }

    .permissions-page .info-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .permissions-page .info-value {
        color: #0f172a;
        font-size: 16px;
        font-weight: 800;
        line-height: 1.4;
    }

    .permissions-page .role-badge,
    .permissions-page .tag-badge,
    .permissions-page .soft-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 700;
    }

    .permissions-page .role-badge {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .permissions-page .tag-badge {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        margin: 0 8px 8px 0;
    }

    .permissions-page .soft-badge {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .permissions-page .group-card {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
        height: 100%;
    }

    .permissions-page .group-header {
        padding: 14px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .permissions-page .group-title {
        margin: 0;
        color: #1e293b;
        font-size: 15px;
        font-weight: 800;
        text-transform: capitalize;
    }

    .permissions-page .group-body {
        padding: 16px;
    }

    .permissions-page .permission-item {
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 12px 14px;
        margin-bottom: 10px;
        background: #fff;
        transition: all .2s ease;
    }

    .permissions-page .permission-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
    }

    .permissions-page .permission-item:last-child {
        margin-bottom: 0;
    }

    .permissions-page .permission-name {
        color: #0f172a;
        font-weight: 800;
        font-size: 13px;
        margin-bottom: 3px;
    }

    .permissions-page .permission-slug {
        color: #64748b;
        font-size: 12px;
    }

    .permissions-page .custom-permission-box {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 14px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        margin-bottom: 12px;
    }

    .permissions-page .custom-control-label {
        cursor: pointer;
        width: 100%;
    }

    .permissions-page .custom-control-input:checked ~ .custom-control-label .permission-name {
        color: #1d4ed8;
    }

    .permissions-page .full-access-card {
        background: linear-gradient(135deg, #ecfeff 0%, #eff6ff 100%);
        border: 1px solid #bfdbfe;
        border-radius: 18px;
        padding: 20px;
        color: #0f172a;
    }

    .permissions-page .full-access-card h5 {
        margin-bottom: 8px;
        font-weight: 800;
    }

    .permissions-page .full-access-card p {
        margin-bottom: 0;
        color: #475569;
        line-height: 1.8;
    }

    .permissions-page .search-box {
        border-radius: 14px !important;
        min-height: 46px;
        border: 1px solid #dbe4ee !important;
        box-shadow: none !important;
    }

    .permissions-page .save-btn {
        border: none;
        border-radius: 14px;
        padding: 12px 22px;
        font-weight: 800;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 14px 24px rgba(37, 99, 235, 0.20);
    }

    .permissions-page .save-btn:hover {
        transform: translateY(-1px);
    }
</style>

@php
    $isManager = $user->role === 'Manager';
    $isSupervisor = $user->role === 'Supervisor';

    $allowedGroups = null;

    if ($isSupervisor) {
        $allowedGroups = [
            'projects',
            'meetings',
            'requests',
            'verification',
            'notifications',
            'messages',
            'admin',
        ];
    }

    $filteredPermissions = collect($permissions)->filter(function ($groupPermissions, $groupName) use ($allowedGroups) {
        if (is_null($allowedGroups)) {
            return true;
        }

        return in_array($groupName, $allowedGroups, true);
    });
@endphp

<div class="pd-ltr-20 xs-pd-20-10 permissions-page">
    <div class="min-height-200px">

        <div class="hero-card">
            <div class="hero-content d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
                <div>
                    <div class="hero-title">{{ __('backend.permissions_show.page_title') }}</div>
                    <p class="hero-text">
                        {{ __('backend.permissions_show.page_subtitle') }}
                    </p>
                </div>

                <a href="{{ route('admin.permissions.index') }}"
                   class="btn btn-light btn-sm"
                   style="border-radius: 10px; font-weight: 700;">
                    {{ __('backend.permissions_show.back') }}
                </a>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h4>{{ __('backend.permissions_show.user_access_profile') }}</h4>
                <div class="section-subtext">
                    {{ __('backend.permissions_show.user_access_profile_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.permissions_show.name') }}</div>
                            <div class="info-value">{{ $user->name }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.permissions_show.email') }}</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.permissions_show.current_role') }}</div>
                            <div class="info-value">
                                <span class="role-badge">{{ $user->role }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($isSupervisor)
                    <div class="alert alert-info border-0 mt-2 mb-0" style="border-radius: 14px; background:#eff6ff; color:#1e3a8a;">
                        {!! __('backend.permissions_show.supervisor_note') !!}
                    </div>
                @endif

                @if($isManager)
                    <div class="full-access-card mt-3">
                        <h5>{{ __('backend.permissions_show.manager_full_access') }}</h5>
                        <p>
                            {{ __('backend.permissions_show.manager_full_access_text') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h5>{{ __('backend.permissions_show.inherited_permissions_from_role') }}</h5>
                <div class="section-subtext">
                    {{ __('backend.permissions_show.inherited_permissions_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    @forelse($filteredPermissions as $group => $groupPermissions)
                        @php
                            $inherited = $groupPermissions->filter(function ($permission) use ($rolePermissionIds) {
                                return in_array($permission->id, $rolePermissionIds);
                            });
                        @endphp

                        @if($inherited->count())
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="group-card">
                                    <div class="group-header">
                                        <h6 class="group-title">{{ ucfirst($group ?: __('backend.permissions_show.general')) }}</h6>
                                        <span class="soft-badge">{{ $inherited->count() }} {{ __('backend.permissions_show.items') }}</span>
                                    </div>
                                    <div class="group-body">
                                        @foreach($inherited as $permission)
                                            <div class="permission-item">
                                                <div class="permission-name">{{ $permission->name }}</div>
                                                <div class="permission-slug">{{ $permission->slug }}</div>
                                                <div class="mt-2">
                                                    <span class="tag-badge">{{ __('backend.permissions_show.inherited') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info mb-0" style="border-radius: 14px;">
                                {{ __('backend.permissions_show.no_inherited_permissions_found') }}
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h5>{{ __('backend.permissions_show.direct_user_permissions') }}</h5>
                <div class="section-subtext">
                    {{ __('backend.permissions_show.direct_user_permissions_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                <div class="mb-4">
                    <input type="text" id="permissionSearch" class="form-control search-box" placeholder="{{ __('backend.permissions_show.search_permissions_placeholder') }}">
                </div>

                <form action="{{ route('admin.permissions.sync', $user->id) }}" method="POST">
                    @csrf

                    <div class="row" id="permissionGroupsWrap">
                        @foreach($filteredPermissions as $group => $groupPermissions)
                            <div class="col-lg-6 mb-4 permission-group-card" data-group="{{ strtolower($group ?: 'general') }}">
                                <div class="group-card">
                                    <div class="group-header">
                                        <h6 class="group-title">{{ ucfirst($group ?: __('backend.permissions_show.general')) }}</h6>
                                        <span class="soft-badge">{{ $groupPermissions->count() }} {{ __('backend.permissions_show.permissions_count') }}</span>
                                    </div>

                                    <div class="group-body">
                                        @foreach($groupPermissions as $permission)
                                            @php
                                                $isInherited = in_array($permission->id, $rolePermissionIds);
                                                $isDirect = in_array($permission->id, $directPermissionIds);
                                            @endphp

                                            <div class="custom-permission-box permission-search-item"
                                                 data-search="{{ strtolower($permission->name . ' ' . $permission->slug . ' ' . ($group ?: 'general')) }}">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox"
                                                           class="custom-control-input"
                                                           id="permission_{{ $permission->id }}"
                                                           name="permissions[]"
                                                           value="{{ $permission->id }}"
                                                           {{ $isDirect ? 'checked' : '' }}>

                                                    <label class="custom-control-label" for="permission_{{ $permission->id }}">
                                                        <div class="permission-name">{{ $permission->name }}</div>
                                                        <div class="permission-slug">{{ $permission->slug }}</div>

                                                        <div class="mt-2">
                                                            @if($isInherited)
                                                                <span class="soft-badge">{{ __('backend.permissions_show.already_inherited_from_role') }}</span>
                                                            @endif

                                                            @if($isDirect)
                                                                <span class="tag-badge">{{ __('backend.permissions_show.directly_assigned') }}</span>
                                                            @endif
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary save-btn">
                            {{ __('backend.permissions_show.save_permissions') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('permissionSearch');
    const items = document.querySelectorAll('.permission-search-item');
    const groups = document.querySelectorAll('.permission-group-card');

    if (!searchInput) return;

    searchInput.addEventListener('input', function () {
        const term = this.value.trim().toLowerCase();

        groups.forEach(group => {
            let visibleCount = 0;
            const groupItems = group.querySelectorAll('.permission-search-item');

            groupItems.forEach(item => {
                const haystack = item.dataset.search || '';
                const matched = haystack.includes(term);

                item.style.display = matched ? '' : 'none';

                if (matched) visibleCount++;
            });

            group.style.display = visibleCount > 0 ? '' : 'none';
        });
    });
});
</script>
@endsection