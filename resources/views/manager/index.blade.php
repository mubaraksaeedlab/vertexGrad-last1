@extends('layouts.app')
@section('title', __('backend.managers_index.page_title'))

@section('content')
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
        --radius-xl: 24px;
        --radius-lg: 20px;
        --radius-md: 16px;
        --radius-sm: 12px;
    }

    body {
        background: var(--page-bg);
    }

    .managers-page {
        padding: 10px 0 24px;
    }

    .page-header-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9fbff 100%);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
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

    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-soft);
        font-weight: 600;
        margin: 0;
    }

    .stat-icon {
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

    .stat-value {
        margin: 18px 0 0;
        font-size: 1.9rem;
        font-weight: 800;
        color: var(--text-main);
        line-height: 1;
    }

    .stat-note {
        margin-top: 8px;
        font-size: 0.82rem;
        color: var(--text-soft);
    }

    .stat-card.stat-all {
        color: var(--info-color);
        background: linear-gradient(135deg, #ffffff 0%, #f2fcfe 100%);
    }

    .stat-card.stat-active {
        color: var(--success-color);
        background: linear-gradient(135deg, #ffffff 0%, #effcf7 100%);
    }

    .stat-card.stat-disabled {
        color: var(--danger-color);
        background: linear-gradient(135deg, #ffffff 0%, #fff3f1 100%);
    }

    .stat-card.stat-pending {
        color: #b88900;
        background: linear-gradient(135deg, #ffffff 0%, #fff9eb 100%);
    }

    .filter-panel {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        padding: 18px;
        margin-bottom: 20px;
    }

    .filter-label {
        font-size: 0.82rem;
        color: var(--text-soft);
        font-weight: 700;
        margin-bottom: 8px;
        display: block;
    }

    .form-control.filter-input,
    .form-select.filter-select {
        min-height: 46px;
        border-radius: 14px;
        border: 1px solid #dfe5ef;
        box-shadow: none;
        padding: 12px 14px;
    }

    .form-control.filter-input:focus,
    .form-select.filter-select:focus {
        border-color: rgba(78, 115, 223, 0.5);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.12);
    }

    .search-btn {
        min-height: 46px;
        border-radius: 14px;
        font-weight: 700;
        padding: 10px 18px;
    }

    .reset-btn {
        min-height: 46px;
        border-radius: 14px;
        font-weight: 700;
        padding: 10px 18px;
        background: #eef2f8;
        color: #344054;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .reset-btn:hover {
        color: #344054;
        text-decoration: none;
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

    .table-wrap {
        padding: 20px 24px 26px;
    }

    .students-table-card {
        border: 1px solid var(--border-color);
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
    }

    .students-table {
        margin-bottom: 0;
    }

    .students-table thead th {
        background: #172033 !important;
        color: #fff !important;
        border: none !important;
        font-size: 0.84rem;
        font-weight: 700 !important;
        padding: 15px 14px;
        vertical-align: middle;
        white-space: nowrap;
        text-align: center !important;
    }

    .students-table tbody td {
        border-color: #eef2f7;
        padding: 15px 14px;
        vertical-align: middle;
        font-size: 0.92rem;
    }

    .students-table tbody tr {
        transition: background 0.2s ease;
    }

    .students-table tbody tr:hover {
        background: #fafcff;
    }

    .student-name-cell {
        font-weight: 700;
        color: var(--text-main);
    }

    .student-email-cell,
    .student-muted-cell {
        color: #667085;
        font-size: 0.9rem;
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
        text-decoration: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }

    .btn-edit {
        background: rgba(78, 115, 223, 0.12);
        color: var(--primary-color);
    }

    .btn-delete {
        background: rgba(231, 74, 59, 0.12);
        color: var(--danger-color);
    }

    .empty-state {
        padding: 32px 18px !important;
        color: var(--text-soft);
        font-weight: 600;
        text-align: center;
        background: #fff;
    }

    .pagination-wrap {
        padding: 0 24px 24px;
    }

    @media (max-width: 991px) {
        .page-header-card {
            padding: 22px 20px;
        }

        .panel-head,
        .table-wrap,
        .pagination-wrap {
            padding-left: 18px;
            padding-right: 18px;
        }
    }

    @media (max-width: 576px) {
        .page-title {
            font-size: 1.3rem;
        }

        .stat-card {
            min-height: 122px;
        }

        .students-table thead th,
        .students-table tbody td {
            white-space: nowrap;
        }
    }
</style>

<div class="container-fluid managers-page">

    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.managers_index.heading') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.managers_index.subtitle') }}
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('manager.index') }}" class="btn btn-light px-4 py-2 rounded-pill fw-semibold">
                    <i class="fa fa-users me-1"></i> {{ __('backend.managers_index.all') }}
                </a>

                <a href="{{ route('manager.create') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                    <i class="fa fa-plus me-1"></i> {{ __('backend.managers_index.add_manager') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 stats-grid mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-all">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.managers_index.total_managers') }}</p>
                    <span class="stat-icon"><i class="bi bi-people-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['total'] }}</h3>
                <div class="stat-note">{{ __('backend.managers_index.total_managers_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-active">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.managers_index.active') }}</p>
                    <span class="stat-icon"><i class="bi bi-check-circle-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['active'] }}</h3>
                <div class="stat-note">{{ __('backend.managers_index.active_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-disabled">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.managers_index.inactive') }}</p>
                    <span class="stat-icon"><i class="bi bi-slash-circle-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['inactive'] }}</h3>
                <div class="stat-note">{{ __('backend.managers_index.inactive_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-pending">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.managers_index.departments') }}</p>
                    <span class="stat-icon"><i class="bi bi-building-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['departments'] ?? 0 }}</h3>
                <div class="stat-note">{{ __('backend.managers_index.departments_note') }}</div>
            </div>
        </div>
    </div>

    <div class="filter-panel">
        <form method="GET" action="{{ route('manager.index') }}" id="managerFilterForm">
            <div class="row g-3 align-items-end">
                <div class="col-lg-2 col-md-6">
                    <label class="filter-label">{{ __('backend.managers_index.show_entries') }}</label>
                    <select name="per_page" id="entries" class="form-select filter-select auto-submit-filter">
                        <option value="10" {{ request('per_page')==10?'selected':'' }}>10</option>
                        <option value="25" {{ request('per_page')==25?'selected':'' }}>25</option>
                        <option value="50" {{ request('per_page')==50?'selected':'' }}>50</option>
                        <option value="100" {{ request('per_page')==100?'selected':'' }}>100</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="filter-label">{{ __('backend.managers_index.status') }}</label>
                    <select name="status" id="statusFilter" class="form-select filter-select auto-submit-filter">
                        <option value="">{{ __('backend.managers_index.all') }}</option>
                        <option value="active" {{ request('status')=='active'?'selected':'' }}>{{ __('backend.managers_index.active') }}</option>
                        <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>{{ __('backend.managers_index.inactive') }}</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary search-btn w-100">
                            <i class="fa fa-search me-1"></i> {{ __('backend.managers_index.apply') }}
                        </button>

                        <a href="{{ route('manager.index') }}" class="reset-btn w-100">
                            {{ __('backend.managers_index.reset') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="main-panel">
        <div class="panel-head">
            <h2 class="panel-title">{{ __('backend.managers_index.managers_list') }}</h2>
            <div class="panel-subtitle">{{ __('backend.managers_index.list_subtitle') }}</div>
        </div>

        <div class="table-wrap">
            <div class="table-responsive students-table-card">
                <table class="data-table table students-table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('backend.managers_index.name') }}</th>
                            <th>{{ __('backend.managers_index.email') }}</th>
                            <th>{{ __('backend.managers_index.department') }}</th>
                            <th>{{ __('backend.managers_index.last_login') }}</th>
                            <th>{{ __('backend.managers_index.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($managers as $manager)
                            <tr id="manager-{{ $manager->id }}">
                                <td class="text-center">
                                    {{ $loop->iteration + ($managers->currentPage() - 1) * $managers->perPage() }}
                                </td>
                                <td>
                                    <div class="student-name-cell">{{ $manager->user->name ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="student-email-cell">{{ $manager->user->email ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="student-muted-cell">{{ $manager->department ?? '—' }}</div>
                                </td>
                                <td>
                                    <div class="student-muted-cell">{{ $manager->last_login ?? '—' }}</div>
                                </td>
                                <td class="text-center">
                                    <div class="actions-group">
                                        <a href="{{ route('manager.edit', $manager->id) }}" class="action-btn btn-edit" title="{{ __('backend.managers_index.edit') }}">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <form action="{{ route('manager.destroy', $manager->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="action-btn btn-delete" onclick="return confirm('{{ __('backend.managers_index.delete_confirm') }}')" title="{{ __('backend.managers_index.delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">{{ __('backend.managers_index.no_managers_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pagination-wrap">
            {{ $managers->links() }}
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.data-table').DataTable({
        "initComplete": function() {
            $('.data-table thead th').css({
                'background-color': '#172033',
                'color': '#ffffff',
                'font-weight': '700',
                'text-align': 'center'
            });
        }
    });

    document.querySelectorAll('.auto-submit-filter').forEach(el => {
        el.addEventListener('change', function () {
            const form = document.getElementById('managerFilterForm');
            if (form) form.submit();
        });
    });

    document.querySelectorAll('.stat-card, .filter-panel, .main-panel, .page-header-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(10px)';

        setTimeout(() => {
            card.style.transition = 'all 0.35s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 70 * (index + 1));
    });
});
</script>
@endpush