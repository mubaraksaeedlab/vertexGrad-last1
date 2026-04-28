@extends('layouts.app')

@section('title', __('backend.students_index.title'))

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

    .students-page {
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
        background: #172033;
        color: #fff;
        border: none;
        font-size: 0.84rem;
        font-weight: 700;
        padding: 15px 14px;
        vertical-align: middle;
        white-space: nowrap;
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

    .badge-pending {
        background: #edf1f7;
        color: #596579;
    }

    .badge-disabled {
        background: var(--danger-soft);
        color: #c7372b;
    }

    .badge-default {
        background: #f5f7fb;
        color: #667085;
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
    }

    .btn-view {
        background: rgba(54, 185, 204, 0.12);
        color: var(--info-color);
    }

    .btn-edit {
        background: rgba(78, 115, 223, 0.12);
        color: var(--primary-color);
    }

    .btn-delete {
        background: rgba(231, 74, 59, 0.12);
        color: var(--danger-color);
    }

    .sortable {
        cursor: pointer;
        user-select: none;
    }

    .sortable i {
        margin-left: 5px;
        color: rgba(255,255,255,0.75);
        font-size: 0.8rem;
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

    .custom-pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .custom-pagination .page-box {
        min-width: 44px;
        height: 44px;
        padding: 0 14px;
        border-radius: 12px;
        border: 1px solid #e3e8f2;
        background: #ffffff;
        color: #172033;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(18, 38, 63, 0.05);
    }

    .custom-pagination .page-box:hover {
        background: #4e73df;
        color: #ffffff;
        border-color: #4e73df;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(78, 115, 223, 0.18);
    }

    .custom-pagination .page-box.active {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: #ffffff;
        border-color: transparent;
        box-shadow: 0 10px 22px rgba(78, 115, 223, 0.28);
    }

    .custom-pagination .page-box.disabled {
        opacity: 0.45;
        pointer-events: none;
        background: #f5f7fb;
        color: #98a2b3;
        box-shadow: none;
    }

    .custom-pagination .page-box.dots {
        border-style: dashed;
        background: #f9fbff;
        color: #7b8497;
        pointer-events: none;
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

        .custom-pagination {
            gap: 8px;
        }

        .custom-pagination .page-box {
            min-width: 40px;
            height: 40px;
            border-radius: 10px;
            font-size: 0.88rem;
            padding: 0 10px;
        }
    }
</style>

@php
    $studentsCollection = $students->getCollection();

    $allCount = $students->total();
    $activeCount = $studentsCollection->where('status', 'active')->count();
    $pendingCount = $studentsCollection->where('status', 'pending')->count();
    $inactiveCount = $studentsCollection->where('status', 'inactive')->count();
    $disabledCount = $studentsCollection->where('status', 'disabled')->count();
@endphp

<div class="container-fluid students-page">

    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.students_index.page_title') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.students_index.page_subtitle') }}
                </p>
            </div>

            <div>
                <a href="{{ route('admin.students.create') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                    <i class="bi bi-plus-lg me-2"></i>{{ __('backend.students_index.add_student') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 stats-grid mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-all" onclick="clearStatusFilter()">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.students_index.all_students') }}</p>
                    <span class="stat-icon"><i class="bi bi-people-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $allCount }}</h3>
                <div class="stat-note">{{ __('backend.students_index.all_students_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-pending" onclick="setStatusFilter('pending')">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.students_index.status_pending') }}</p>
                    <span class="stat-icon"><i class="bi bi-hourglass-split"></i></span>
                </div>
                <h3 class="stat-value">{{ $pendingCount }}</h3>
                <div class="stat-note">{{ __('backend.students_index.pending_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-active" onclick="setStatusFilter('active')">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.students_index.status_active') }}</p>
                    <span class="stat-icon"><i class="bi bi-check-circle-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $activeCount }}</h3>
                <div class="stat-note">{{ __('backend.students_index.active_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-inactive" onclick="setStatusFilter('inactive')">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.students_index.status_inactive') }}</p>
                    <span class="stat-icon"><i class="bi bi-pause-circle-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $inactiveCount }}</h3>
                <div class="stat-note">{{ __('backend.students_index.inactive_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-disabled" onclick="setStatusFilter('disabled')">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.students_index.status_disabled') }}</p>
                    <span class="stat-icon"><i class="bi bi-slash-circle-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $disabledCount }}</h3>
                <div class="stat-note">{{ __('backend.students_index.disabled_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-add" onclick="window.location.href='{{ route('admin.students.create') }}'">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.students_index.quick_create') }}</p>
                    <span class="stat-icon"><i class="bi bi-person-plus-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ __('backend.students_index.new') }}</h3>
                <div class="stat-note">{{ __('backend.students_index.quick_create_note') }}</div>
            </div>
        </div>
    </div>

    <div class="filter-panel">
        <form method="GET" action="{{ route('admin.students.index') }}" id="studentsFilterForm">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="filter-label">{{ __('backend.students_index.search') }}</label>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        class="form-control filter-input"
                        placeholder="{{ __('backend.students_index.search_placeholder') }}">
                </div>

                <div class="col-lg-3 col-md-6">
                    <label class="filter-label">{{ __('backend.students_index.status') }}</label>
                    <select id="statusFilter" name="status" class="form-select filter-select">
                        <option value="">{{ __('backend.students_index.all') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('backend.students_index.status_active') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('backend.students_index.status_pending') }}</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('backend.students_index.status_inactive') }}</option>
                        <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>{{ __('backend.students_index.status_disabled') }}</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <label class="filter-label">{{ __('backend.students_index.show_entries') }}</label>
                    <select id="entries" name="per_page" class="form-select filter-select">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <div class="col-lg-3 col-md-8">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary search-btn w-100">
                            <i class="bi bi-search me-2"></i>{{ __('backend.students_index.apply') }}
                        </button>
                        <a href="{{ route('admin.students.index') }}" class="reset-btn w-100">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>{{ __('backend.students_index.reset') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="main-panel">
        <div class="panel-head">
            <h2 class="panel-title">{{ __('backend.students_index.directory_title') }}</h2>
            <div class="panel-subtitle">{{ __('backend.students_index.directory_subtitle') }}</div>
        </div>

        <div class="table-wrap">
            <div class="table-responsive students-table-card">
                <table id="studentsTable" class="table students-table align-middle">
                    <thead>
                        <tr>
                            <th class="sortable text-center">{{ __('backend.students_index.name') }} <i class="bi"></i></th>
                            <th class="sortable text-center">{{ __('backend.students_index.email') }} <i class="bi"></i></th>
                            <th class="sortable text-center">{{ __('backend.students_index.major') }} <i class="bi"></i></th>
                            <th class="sortable text-center">{{ __('backend.students_index.phone') }} <i class="bi"></i></th>
                            <th class="sortable text-center">{{ __('backend.students_index.address') }} <i class="bi"></i></th>
                            <th class="sortable text-center">{{ __('backend.students_index.status') }} <i class="bi"></i></th>
                            <th class="text-center">{{ __('backend.students_index.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($students as $user)
                            <tr>
                                <td>
                                    <div class="student-name-cell">{{ $user->name ?? '—' }}</div>
                                </td>

                                <td>
                                    <div class="student-email-cell">{{ $user->email ?? '—' }}</div>
                                </td>

                                <td>
                                    <div class="student-muted-cell">{{ $user->student?->major ?? '—' }}</div>
                                </td>

                                <td>
                                    <div class="student-muted-cell">{{ $user->student?->phone ?? '—' }}</div>
                                </td>

                                <td>
                                    <div class="student-muted-cell">{{ $user->student?->address ?? '—' }}</div>
                                </td>

                                <td class="text-center">
                                    @php
                                        $statusClass = match($user->status) {
                                            'active' => 'badge-active',
                                            'inactive' => 'badge-inactive',
                                            'pending' => 'badge-pending',
                                            'disabled' => 'badge-disabled',
                                            default => 'badge-default',
                                        };
                                    @endphp

                                    <span class="badge-status {{ $statusClass }}">
                                        {{ match($user->status) {
                                            'active' => __('backend.students_index.status_active'),
                                            'inactive' => __('backend.students_index.status_inactive'),
                                            'pending' => __('backend.students_index.status_pending'),
                                            'disabled' => __('backend.students_index.status_disabled'),
                                            default => '—',
                                        } }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="actions-group">
                                        <a href="{{ route('admin.students.show', $user->id) }}" class="action-btn btn-view" title="{{ __('backend.students_index.view') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.students.edit', $user->id) }}" class="action-btn btn-edit" title="{{ __('backend.students_index.edit') }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.students.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete" onclick="return confirm('{{ __('backend.students_index.delete_confirm') }}')" title="{{ __('backend.students_index.delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">{{ __('backend.students_index.no_students_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pagination-wrap">
            @if ($students->hasPages())
                <div class="custom-pagination">
                    @if ($students->onFirstPage())
                        <span class="page-box disabled">‹</span>
                    @else
                        <a href="{{ $students->appends(request()->query())->previousPageUrl() }}" class="page-box">‹</a>
                    @endif

                    @php
                        $current = $students->currentPage();
                        $last = $students->lastPage();

                        $start = max($current - 1, 1);
                        $end = min($current + 1, $last);

                        if ($current <= 2) {
                            $end = min(3, $last);
                        }

                        if ($current >= $last - 1) {
                            $start = max($last - 2, 1);
                        }
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $students->appends(request()->query())->url(1) }}" class="page-box">1</a>

                        @if ($start > 2)
                            <span class="page-box dots">...</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $current)
                            <span class="page-box active">{{ $page }}</span>
                        @else
                            <a href="{{ $students->appends(request()->query())->url($page) }}" class="page-box">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="page-box dots">...</span>
                        @endif

                        <a href="{{ $students->appends(request()->query())->url($last) }}" class="page-box">{{ $last }}</a>
                    @endif

                    @if ($students->hasMorePages())
                        <a href="{{ $students->appends(request()->query())->nextPageUrl() }}" class="page-box">›</a>
                    @else
                        <span class="page-box disabled">›</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function setStatusFilter(status) {
        const url = new URL(window.location.href);
        url.searchParams.set('status', status);
        window.location.href = url.toString();
    }

    function clearStatusFilter() {
        const url = new URL(window.location.href);
        url.searchParams.delete('status');
        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const entries = document.getElementById('entries');
        const statusFilter = document.getElementById('statusFilter');
        const filterForm = document.getElementById('studentsFilterForm');

        if (entries) {
            entries.addEventListener('change', function () {
                filterForm.submit();
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', function () {
                filterForm.submit();
            });
        }

        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

        const comparer = (idx, asc) => (a, b) => {
            const v1 = getCellValue(asc ? a : b, idx).trim();
            const v2 = getCellValue(asc ? b : a, idx).trim();

            const n1 = parseFloat(v1);
            const n2 = parseFloat(v2);

            if (!isNaN(n1) && !isNaN(n2)) {
                return n1 - n2;
            }

            return v1.localeCompare(v2);
        };

        document.querySelectorAll('.sortable').forEach(th => {
            th.addEventListener('click', function () {
                const table = th.closest('table');
                const tbody = table.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr')).filter(tr => tr.children.length > 1);
                const index = Array.from(th.parentNode.children).indexOf(th);

                rows.sort(comparer(index, this.asc = !this.asc)).forEach(tr => tbody.appendChild(tr));

                table.querySelectorAll('.sortable i').forEach(i => i.className = 'bi');
                this.querySelector('i').className = this.asc ? 'bi bi-caret-up-fill' : 'bi bi-caret-down-fill';
            });
        });

        document.querySelectorAll('.stat-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(10px)';

            setTimeout(() => {
                card.style.transition = 'all 0.35s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 80 * (index + 1));
        });
    });
</script>
@endsection