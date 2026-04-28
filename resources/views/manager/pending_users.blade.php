@extends('layouts.app')

@section('title', __('backend.users_management.page_title'))

@section('content')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    :root {
        --page-bg: #f5f7fb;
        --card-bg: #ffffff;
        --text-main: #172033;
        --text-soft: #7b8497;
        --border-color: #e8ecf4;
        --primary-color: #4e73df;
        --primary-soft: rgba(78, 115, 223, 0.10);
        --info-color: #36b9cc;
        --info-soft: rgba(54, 185, 204, 0.12);
        --success-color: #1cc88a;
        --success-soft: rgba(28, 200, 138, 0.12);
        --warning-color: #f6c23e;
        --warning-soft: rgba(246, 194, 62, 0.14);
        --danger-color: #e74a3b;
        --danger-soft: rgba(231, 74, 59, 0.12);
        --shadow-sm: 0 8px 20px rgba(18, 38, 63, 0.06);
        --shadow-md: 0 14px 36px rgba(18, 38, 63, 0.10);
        --radius-lg: 20px;
        --radius-md: 16px;
        --radius-sm: 12px;
    }

    body {
        background: var(--page-bg);
    }

    .users-page {
        padding: 10px 0 24px;
    }

    .page-header-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9fbff 100%);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        padding: 26px 28px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .page-subtitle {
        margin: 8px 0 0;
        color: var(--text-soft);
        font-size: 0.96rem;
    }

    .custom-alert {
        border: none;
        border-radius: 14px;
        box-shadow: var(--shadow-sm);
    }

    .stats-grid .col-lg-2,
    .stats-grid .col-md-3,
    .stats-grid .col-sm-6 {
        display: flex;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
        width: 100%;
        min-height: 132px;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        background: var(--card-bg);
        padding: 20px 18px;
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .stat-card::after {
        content: "";
        position: absolute;
        top: -35px;
        right: -35px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        opacity: 0.08;
        background: currentColor;
    }

    .stat-card .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-card .stat-label {
        font-size: 0.9rem;
        color: var(--text-soft);
        font-weight: 600;
        margin: 0;
    }

    .stat-card .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        background: rgba(255,255,255,0.65);
        backdrop-filter: blur(4px);
    }

    .stat-card .stat-value {
        margin: 18px 0 0;
        font-size: 1.9rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1;
    }

    .stat-card .stat-note {
        margin-top: 8px;
        font-size: 0.82rem;
        color: var(--text-soft);
    }

    .stat-card.stat-all {
        color: var(--info-color);
        background: linear-gradient(135deg, #ffffff 0%, #f2fcfe 100%);
    }

    .stat-card.stat-pending {
        color: #b88900;
        background: linear-gradient(135deg, #ffffff 0%, #fff9eb 100%);
    }

    .stat-card.stat-active {
        color: var(--success-color);
        background: linear-gradient(135deg, #ffffff 0%, #effcf7 100%);
    }

    .stat-card.stat-inactive {
        color: #8a6d1d;
        background: linear-gradient(135deg, #ffffff 0%, #fffaf0 100%);
    }

    .stat-card.stat-disabled {
        color: var(--danger-color);
        background: linear-gradient(135deg, #ffffff 0%, #fff3f1 100%);
    }

    .stat-card.stat-add {
        color: var(--primary-color);
        background: linear-gradient(135deg, #eef3ff 0%, #ffffff 100%);
    }

    .main-panel {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .panel-head {
        padding: 22px 24px 10px;
        border-bottom: 1px solid rgba(232, 236, 244, 0.7);
    }

    .panel-title {
        margin: 0;
        font-size: 1.08rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .panel-subtitle {
        margin-top: 6px;
        color: var(--text-soft);
        font-size: 0.9rem;
    }

    .users-tabs-wrap {
        padding: 16px 24px 0;
    }

    .users-tabs {
        gap: 10px;
        border: none;
        flex-wrap: wrap;
    }

    .users-tabs .nav-link {
        border: none;
        border-radius: 999px;
        padding: 10px 18px;
        font-weight: 700;
        color: #667085;
        background: #f6f8fc;
        transition: all 0.2s ease;
    }

    .users-tabs .nav-link:hover {
        color: var(--primary-color);
        background: #edf2ff;
    }

    .users-tabs .nav-link.active {
        background: linear-gradient(135deg, var(--primary-color), #6f8df3);
        color: #fff;
        box-shadow: 0 8px 18px rgba(78, 115, 223, 0.24);
    }

    .table-wrap {
        padding: 20px 24px 26px;
    }

    .users-table-card {
        border: 1px solid var(--border-color);
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
    }

    .table {
        margin-bottom: 0;
    }

    .users-table thead th {
        background: #172033;
        color: #fff;
        border: none;
        font-size: 0.84rem;
        font-weight: 700;
        padding: 15px 14px;
        vertical-align: middle;
        white-space: nowrap;
    }

    .users-table tbody td {
        border-color: #eef2f7;
        padding: 15px 14px;
        vertical-align: middle;
        font-size: 0.92rem;
    }

    .users-table tbody tr {
        transition: background 0.2s ease;
    }

    .users-table tbody tr:hover {
        background: #fafcff;
    }

    .user-name-cell {
        font-weight: 700;
        color: var(--text-main);
    }

    .user-email-cell {
        color: #667085;
        font-size: 0.9rem;
    }

    .badge-status {
        border-radius: 999px;
        font-weight: 700;
        padding: 8px 12px;
        font-size: 0.78rem;
        letter-spacing: 0.2px;
    }

    .badge-active {
        background: var(--success-soft);
        color: #0f8f60;
    }

    .badge-inactive {
        background: var(--warning-soft);
        color: #9a7400;
    }

    .badge-disabled {
        background: var(--danger-soft);
        color: #c7372b;
    }

    .badge-pending,
    .badge-secondary-custom {
        background: #edf1f7;
        color: #596579;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f6f8fc;
        color: #475467;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .actions-group {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        box-shadow: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

    .btn-edit {
        background: rgba(78, 115, 223, 0.12);
        color: var(--primary-color);
    }

    .btn-delete {
        background: rgba(231, 74, 59, 0.12);
        color: var(--danger-color);
    }

    .btn-logout {
        background: rgba(246, 194, 62, 0.16);
        color: #a07000;
    }

    .btn-info-soft {
        background: rgba(54, 185, 204, 0.12);
        color: var(--info-color);
    }

    .sort-arrows {
        display: inline-flex;
        align-items: center;
        margin-left: 6px;
        gap: 3px;
    }

    .sort-arrows i {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.72);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .sort-arrows i:hover {
        color: #ffffff;
        transform: scale(1.12);
    }

    .empty-state {
        padding: 32px 18px !important;
        color: var(--text-soft);
        font-weight: 600;
        text-align: center;
        background: #fff;
    }

    .modal-backdrop {
        background: rgba(15, 23, 42, 0.28) !important;
    }

    .modal-backdrop.show {
        opacity: 1 !important;
    }

    .modern-modal .modal-content {
        border: none;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.22);
    }

    .modern-modal .modal-header {
        border: none;
        padding: 22px 24px 12px;
        background: linear-gradient(135deg, #ffffff 0%, #f7faff 100%);
    }

    .modern-modal .modal-title {
        font-weight: 800;
        color: var(--text-main);
    }

    .modern-modal .modal-body {
        padding: 10px 24px 22px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .detail-card {
        background: #f8faff;
        border: 1px solid #edf1f7;
        border-radius: 16px;
        padding: 14px 15px;
    }

    .detail-label {
        display: block;
        font-size: 0.78rem;
        color: var(--text-soft);
        margin-bottom: 6px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .detail-value {
        font-size: 0.95rem;
        color: var(--text-main);
        font-weight: 700;
        word-break: break-word;
    }

    .modern-modal .modal-footer {
        border: none;
        padding: 0 24px 24px;
    }

    .btn-close-modal {
        background: #eef2f8;
        color: #344054;
        border: none;
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 700;
    }

    @media (max-width: 991px) {
        .page-header-card {
            padding: 22px 20px;
        }

        .panel-head,
        .users-tabs-wrap,
        .table-wrap {
            padding-left: 18px;
            padding-right: 18px;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 1.3rem;
        }

        .stat-card {
            min-height: 122px;
        }

        .users-tabs .nav-link {
            width: 100%;
            text-align: center;
        }

        .users-table thead th,
        .users-table tbody td {
            white-space: nowrap;
        }
    }
</style>

<div class="container-fluid users-page">

    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.users_management.heading') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.users_management.subtitle') }}
                </p>
            </div>

            <div>
                <a href="{{ route('manager.users.create') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                    <i class="bi bi-person-plus-fill me-2"></i>
                    {{ __('backend.users_management.add_new_user') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 stats-grid mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-all" onclick="showTab('allTab')">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.users_management.all_users') }}</p>
                    <span class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </span>
                </div>
                <h3 class="stat-value">{{ $allUsers->count() }}</h3>
                <div class="stat-note">{{ __('backend.users_management.all_users_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-pending" onclick="showTab('pendingTab')">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.users_management.pending') }}</p>
                    <span class="stat-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                </div>
                <h3 class="stat-value">{{ $pendingCount }}</h3>
                <div class="stat-note">{{ __('backend.users_management.pending_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-active" onclick="showTab('activeTab')">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.users_management.active') }}</p>
                    <span class="stat-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </span>
                </div>
                <h3 class="stat-value">{{ $activeCount }}</h3>
                <div class="stat-note">{{ __('backend.users_management.active_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-inactive" onclick="showTab('inactiveTab')">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.users_management.inactive') }}</p>
                    <span class="stat-icon">
                        <i class="bi bi-pause-circle-fill"></i>
                    </span>
                </div>
                <h3 class="stat-value">{{ $inactiveCount }}</h3>
                <div class="stat-note">{{ __('backend.users_management.inactive_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-disabled" onclick="showTab('disabledTab')">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.users_management.disabled') }}</p>
                    <span class="stat-icon">
                        <i class="bi bi-slash-circle-fill"></i>
                    </span>
                </div>
                <h3 class="stat-value">{{ $disabledCount }}</h3>
                <div class="stat-note">{{ __('backend.users_management.disabled_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-add" onclick="window.location.href='{{ route('manager.users.create') }}'">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.users_management.quick_create') }}</p>
                    <span class="stat-icon">
                        <i class="bi bi-person-plus-fill"></i>
                    </span>
                </div>
                <h3 class="stat-value">{{ __('backend.users_management.new') }}</h3>
                <div class="stat-note">{{ __('backend.users_management.quick_create_note') }}</div>
            </div>
        </div>
    </div>

    <div class="main-panel">
        <div class="panel-head">
            <h2 class="panel-title">{{ __('backend.users_management.users_directory') }}</h2>
            <div class="panel-subtitle">{{ __('backend.users_management.users_directory_subtitle') }}</div>
        </div>

        <div class="users-tabs-wrap">
            <ul class="nav users-tabs" id="usersTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#allTab" role="tab">{{ __('backend.users_management.all_users') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#pendingTab" role="tab">{{ __('backend.users_management.pending') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#activeTab" role="tab">{{ __('backend.users_management.active') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#inactiveTab" role="tab">{{ __('backend.users_management.inactive') }}</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#disabledTab" role="tab">{{ __('backend.users_management.disabled') }}</a>
                </li>
            </ul>
        </div>

        @php
            $tabs = [
                'allTab' => $allUsers,
                'pendingTab' => $pendingUsers,
                'activeTab' => $activeUsers,
                'inactiveTab' => $inactiveUsers,
                'disabledTab' => $disabledUsers,
            ];
        @endphp

        <div class="tab-content">
            @foreach ($tabs as $tabId => $users)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $tabId }}">
                    <div class="table-wrap">
                        <div class="table-responsive users-table-card">
                            <table class="table users-table align-middle">
                                <thead>
                                    <tr>
                                        <th>
                                            #
                                            <span class="sort-arrows">
                                                <i class="bi bi-caret-up-fill" onclick="sortTable(this,'asc')"></i>
                                                <i class="bi bi-caret-down-fill" onclick="sortTable(this,'desc')"></i>
                                            </span>
                                        </th>
                                        <th>
                                            {{ __('backend.users_management.name') }}
                                            <span class="sort-arrows">
                                                <i class="bi bi-caret-up-fill" onclick="sortTable(this,'asc')"></i>
                                                <i class="bi bi-caret-down-fill" onclick="sortTable(this,'desc')"></i>
                                            </span>
                                        </th>
                                        <th>
                                            {{ __('backend.users_management.email') }}
                                            <span class="sort-arrows">
                                                <i class="bi bi-caret-up-fill" onclick="sortTable(this,'asc')"></i>
                                                <i class="bi bi-caret-down-fill" onclick="sortTable(this,'desc')"></i>
                                            </span>
                                        </th>
                                        <th>
                                            {{ __('backend.users_management.status') }}
                                            <span class="sort-arrows">
                                                <i class="bi bi-caret-up-fill" onclick="sortTable(this,'asc')"></i>
                                                <i class="bi bi-caret-down-fill" onclick="sortTable(this,'desc')"></i>
                                            </span>
                                        </th>
                                        <th>
                                            {{ __('backend.users_management.role') }}
                                            <span class="sort-arrows">
                                                <i class="bi bi-caret-up-fill" onclick="sortTable(this,'asc')"></i>
                                                <i class="bi bi-caret-down-fill" onclick="sortTable(this,'desc')"></i>
                                            </span>
                                        </th>
                                        <th>
                                            {{ __('backend.users_management.last_login') }}
                                            <span class="sort-arrows">
                                                <i class="bi bi-caret-up-fill" onclick="sortTable(this,'asc')"></i>
                                                <i class="bi bi-caret-down-fill" onclick="sortTable(this,'desc')"></i>
                                            </span>
                                        </th>
                                        <th class="text-center">{{ __('backend.users_management.actions') }}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                <div class="user-name-cell">{{ $user->name }}</div>
                                            </td>

                                            <td>
                                                <div class="user-email-cell">{{ $user->email }}</div>
                                            </td>

                                            <td>
                                                @php
                                                    $statusClass = match($user->status) {
                                                        'active' => 'badge-active',
                                                        'inactive' => 'badge-inactive',
                                                        'disabled' => 'badge-disabled',
                                                        'pending' => 'badge-pending',
                                                        default => 'badge-secondary-custom',
                                                    };
                                                @endphp
                                                <span class="badge-status {{ $statusClass }}">
                                                    {{ ucfirst($user->status) }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="role-badge">
                                                    <i class="bi bi-shield-check"></i>
                                                    {{ $user->role }}
                                                </span>
                                            </td>

                                            <td>{{ $user->last_login ?? '—' }}</td>

                                            <td class="text-center">
                                                <div class="actions-group">
                                                    <a href="{{ route('manager.users.edit', $user->id) }}"
                                                       class="action-btn btn-edit"
                                                       title="{{ __('backend.users_management.edit') }}">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <form action="{{ route('manager.users.destroy', $user->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('{{ __('backend.users_management.confirm_delete') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-btn btn-delete" title="{{ __('backend.users_management.delete') }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>

                                                    <form action="{{ route('manager.users.force-logout', $user->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('{{ __('backend.users_management.confirm_force_logout') }}');">
                                                        @csrf
                                                        <button type="submit" class="action-btn btn-logout" title="{{ __('backend.users_management.force_logout') }}">
                                                            <i class="bi bi-box-arrow-right"></i>
                                                        </button>
                                                    </form>

                                                    <button type="button"
                                                            class="action-btn btn-info-soft btn-show-details"
                                                            data-user-id="{{ $user->id }}"
                                                            title="{{ __('backend.users_management.view_details') }}">
                                                        <i class="bi bi-info-circle"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="empty-state">{{ __('backend.users_management.no_users_found') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade modern-modal" id="userDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-1">{{ __('backend.users_management.user_details') }}</h5>
                    <div class="text-muted small">{{ __('backend.users_management.user_details_subtitle') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="userDetailsContent">
                    <div class="text-center text-muted py-4">{{ __('backend.users_management.loading_user_details') }}</div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal">{{ __('backend.users_management.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showTab(tabId) {
        const tabTriggerEl = document.querySelector(`a[href="#${tabId}"]`);
        if (tabTriggerEl) {
            const tab = new bootstrap.Tab(tabTriggerEl);
            tab.show();
        }
    }

    function safeValue(value) {
        return value ?? '—';
    }

    document.addEventListener('DOMContentLoaded', function () {
        const detailsModalEl = document.getElementById('userDetailsModal');
        const detailsModal = new bootstrap.Modal(detailsModalEl);
        const detailsContent = document.getElementById('userDetailsContent');

        document.querySelectorAll('.btn-show-details').forEach(button => {
            button.addEventListener('click', function () {
                const userId = this.dataset.userId;

                detailsContent.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                        ${@json(__('backend.users_management.loading_user_details'))}
                    </div>
                `;

                detailsModal.show();

                fetch(`/manager/users/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        detailsContent.innerHTML = `
                            <div class="details-grid">
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.username'))}</span>
                                    <div class="detail-value">${safeValue(data.username)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.full_name'))}</span>
                                    <div class="detail-value">${safeValue(data.name)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.email'))}</span>
                                    <div class="detail-value">${safeValue(data.email)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.role'))}</span>
                                    <div class="detail-value">${safeValue(data.role)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.status'))}</span>
                                    <div class="detail-value">${safeValue(data.status)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.gender'))}</span>
                                    <div class="detail-value">${safeValue(data.gender)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.city'))}</span>
                                    <div class="detail-value">${safeValue(data.city)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.state'))}</span>
                                    <div class="detail-value">${safeValue(data.state)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.last_login'))}</span>
                                    <div class="detail-value">${safeValue(data.last_login)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.last_activity'))}</span>
                                    <div class="detail-value">${safeValue(data.last_activity)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.ip_address'))}</span>
                                    <div class="detail-value">${safeValue(data.login_ip)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.device'))}</span>
                                    <div class="detail-value">${safeValue(data.device)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.browser'))}</span>
                                    <div class="detail-value">${safeValue(data.browser)}</div>
                                </div>
                                <div class="detail-card">
                                    <span class="detail-label">${@json(__('backend.users_management.operating_system'))}</span>
                                    <div class="detail-value">${safeValue(data.os)}</div>
                                </div>
                            </div>
                        `;
                    })
                    .catch(() => {
                        detailsContent.innerHTML = `
                            <div class="alert alert-danger mb-0 rounded-4">
                                ${@json(__('backend.users_management.failed_fetch_user_data'))}
                            </div>
                        `;
                    });
            });
        });
    });

    function sortTable(el, order) {
        const th = el.closest('th');
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        const index = Array.from(th.parentNode.children).indexOf(th);

        const rows = Array.from(tbody.querySelectorAll('tr'));
        const normalRows = rows.filter(row => row.children.length > 1);

        normalRows.sort((a, b) => {
            let aText = a.children[index]?.innerText.trim().toLowerCase() || '';
            let bText = b.children[index]?.innerText.trim().toLowerCase() || '';

            const aNum = parseFloat(aText);
            const bNum = parseFloat(bText);

            if (!isNaN(aNum) && !isNaN(bNum)) {
                return order === 'asc' ? aNum - bNum : bNum - aNum;
            }

            return order === 'asc'
                ? aText.localeCompare(bText)
                : bText.localeCompare(aText);
        });

        normalRows.forEach(row => tbody.appendChild(row));
    }
</script>
@endsection