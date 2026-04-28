@extends('layouts.app')

@section('title', __('backend.investors_index.page_title'))

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

    .investors-page {
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
        line-height: 1.1;
        word-break: break-word;
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

    .filter-header-row {
        margin-bottom: 18px;
    }

    .filter-nav {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
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

    .view-btn {
        min-height: 46px;
        border-radius: 14px;
        font-weight: 700;
        padding: 10px 16px;
        background: #fff;
        color: #344054;
        border: 1px solid #dfe5ef;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all .2s ease;
    }

    .view-btn:hover {
        text-decoration: none;
        color: #344054;
        border-color: #cfd8e6;
        background: #fafcff;
    }

    .view-btn.active {
        background: linear-gradient(135deg, #4e73df, #6f8df3);
        color: #fff !important;
        border-color: transparent;
        box-shadow: 0 10px 20px rgba(78, 115, 223, 0.15);
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

    .mini-text {
        color: #667085;
        font-size: 0.84rem;
        margin-top: 3px;
    }

    .badge-status,
    .badge-soft {
        border-radius: 999px;
        font-weight: 700;
        padding: 8px 12px;
        font-size: 0.78rem;
        letter-spacing: 0.2px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .badge-status-active {
        background: var(--success-soft);
        color: #0f8f60;
    }

    .badge-status-inactive {
        background: var(--warning-soft);
        color: #9a7400;
    }

    .badge-status-default {
        background: #edf1f7;
        color: #596579;
    }

    .badge-archived {
        background: var(--danger-soft);
        color: #c7372b;
    }

    .badge-funding-requested {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .badge-funding-interested {
        background: #fff7ed;
        color: #c2410c;
    }

    .badge-funding-approved {
        background: #ecfdf5;
        color: #15803d;
    }

    .badge-funding-rejected {
        background: #fef2f2;
        color: #dc2626;
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
        background: #f8fafc;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        text-decoration: none;
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

    .btn-restore {
        background: rgba(28, 200, 138, 0.12);
        color: #0f8f60;
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

        .filter-nav {
            justify-content: flex-start;
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

<div class="container-fluid investors-page">

    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.investors_index.heading') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.investors_index.subtitle') }}
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.investors.export', 'xlsx') }}" class="btn btn-light px-4 py-2 rounded-pill fw-semibold">
                    <i class="fa fa-file-excel mr-1"></i> {{ __('backend.investors_index.export_excel') }}
                </a>

                <a href="{{ route('admin.investors.create') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                    <i class="fa fa-plus mr-1"></i> {{ __('backend.investors_index.add_investor') }}
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show custom-alert mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3 stats-grid mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-all">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.investors_index.total_investors') }}</p>
                    <span class="stat-icon"><i class="bi bi-people-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['total'] ?? 0 }}</h3>
                <div class="stat-note">{{ __('backend.investors_index.total_investors_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-active">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.investors_index.active') }}</p>
                    <span class="stat-icon"><i class="bi bi-check-circle-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['active'] ?? 0 }}</h3>
                <div class="stat-note">{{ __('backend.investors_index.active_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-inactive">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.investors_index.inactive') }}</p>
                    <span class="stat-icon"><i class="bi bi-pause-circle-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['inactive'] ?? 0 }}</h3>
                <div class="stat-note">{{ __('backend.investors_index.inactive_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-pending">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.investors_index.archived') }}</p>
                    <span class="stat-icon"><i class="bi bi-archive-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $stats['archived'] ?? 0 }}</h3>
                <div class="stat-note">{{ __('backend.investors_index.archived_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-add">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.investors_index.total_budget') }}</p>
                    <span class="stat-icon"><i class="bi bi-cash-stack"></i></span>
                </div>
                <h3 class="stat-value" style="font-size: 1.15rem;">${{ number_format($stats['budget'] ?? 0, 2) }}</h3>
                <div class="stat-note">{{ __('backend.investors_index.total_budget_note') }}</div>
            </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="stat-card stat-disabled">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.investors_index.top_company') }}</p>
                    <span class="stat-icon"><i class="bi bi-building-fill"></i></span>
                </div>
                <h3 class="stat-value" style="font-size: 1rem; line-height: 1.35;">
                    {{ $stats['top_company']->company ?? __('backend.investors_index.not_available') }}
                </h3>
                <div class="stat-note">{{ __('backend.investors_index.top_company_note') }}</div>
            </div>
        </div>
    </div>

    <div class="filter-panel">
        <div class="filter-header-row">
            <div class="row g-3 align-items-center">
                <div class="col-lg-5">
                    <h2 class="panel-title mb-1">{{ __('backend.investors_index.filters') }}</h2>
                    <div class="panel-subtitle">{{ __('backend.investors_index.filters_subtitle') }}</div>
                </div>

                <div class="col-lg-7">
                    <div class="filter-nav">
                        <a href="{{ route('admin.investors.index', ['view' => 'active']) }}"
                           class="view-btn {{ $view === 'active' ? 'active' : '' }}">
                            <i class="fa fa-users"></i> {{ __('backend.investors_index.active_investors') }}
                        </a>

                        <a href="{{ route('admin.investors.index', ['view' => 'archived']) }}"
                           class="view-btn {{ $view === 'archived' ? 'active' : '' }}">
                            <i class="fa fa-archive"></i> {{ __('backend.investors_index.archived_investors') }}
                        </a>

                        <a href="{{ route('admin.investors.index', ['view' => 'all']) }}"
                           class="view-btn {{ $view === 'all' ? 'active' : '' }}">
                            <i class="fa fa-list"></i> {{ __('backend.investors_index.all') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.investors.index') }}" id="investorFilterForm">
            <input type="hidden" name="view" value="{{ request('view', 'active') }}">

            <div class="row g-3 align-items-end">
                <div class="col-lg-4 col-md-6">
                    <label class="filter-label">{{ __('backend.investors_index.search') }}</label>
                    <input
                        type="text"
                        name="search"
                        class="form-control filter-input"
                        placeholder="{{ __('backend.investors_index.search_placeholder') }}"
                        value="{{ request('search') }}">
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="filter-label">{{ __('backend.investors_index.status') }}</label>
                    <select name="status" class="form-select filter-select auto-submit-filter">
                        <option value="">{{ __('backend.investors_index.all_statuses') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('backend.investors_index.active') }}</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('backend.investors_index.inactive') }}</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="filter-label">{{ __('backend.investors_index.city') }}</label>
                    <input
                        type="text"
                        name="city"
                        class="form-control filter-input"
                        placeholder="{{ __('backend.investors_index.city') }}"
                        value="{{ request('city') }}">
                </div>

                <div class="col-lg-2 col-md-6">
                    <label class="filter-label">{{ __('backend.investors_index.per_page') }}</label>
                    <select name="per_page" class="form-select filter-select auto-submit-filter" id="entries">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-12">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary search-btn w-100">
                            <i class="fa fa-search mr-1"></i> {{ __('backend.investors_index.filter') }}
                        </button>

                        <a href="{{ route('admin.investors.index', ['view' => request('view', 'active')]) }}"
                           class="reset-btn w-100">
                            {{ __('backend.investors_index.reset') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="main-panel">
        <div class="panel-head">
            <h2 class="panel-title">
                @if($view === 'archived')
                    {{ __('backend.investors_index.archived_investors') }}
                @elseif($view === 'all')
                    {{ __('backend.investors_index.all_investors') }}
                @else
                    {{ __('backend.investors_index.active_investors') }}
                @endif
            </h2>
            <div class="panel-subtitle">{{ __('backend.investors_index.list_subtitle') }}</div>
        </div>

        <div class="table-wrap">
            <div class="table-responsive students-table-card">
                <table class="table students-table align-middle" id="investorsTable">
                    <thead>
                        <tr>
                            <th class="sortable text-center"># <i class="bi"></i></th>
                            <th class="sortable">{{ __('backend.investors_index.investor') }} <i class="bi"></i></th>
                            <th class="sortable">{{ __('backend.investors_index.company') }} <i class="bi"></i></th>
                            <th class="sortable">{{ __('backend.investors_index.contact') }} <i class="bi"></i></th>
                            <th class="sortable">{{ __('backend.investors_index.type') }} <i class="bi"></i></th>
                            <th class="sortable text-center">{{ __('backend.investors_index.budget') }} <i class="bi"></i></th>
                            <th class="sortable text-center">{{ __('backend.investors_index.status') }} <i class="bi"></i></th>
                            <th>{{ __('backend.investors_index.engagement') }}</th>
                            <th class="sortable text-center">{{ __('backend.investors_index.created') }} <i class="bi"></i></th>
                            <th class="text-center">{{ __('backend.investors_index.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($investors as $inv)
                            @php
                                $profile = $inv->investor;

                                $statusClass = match($inv->status) {
                                    'active' => 'badge-status-active',
                                    'inactive' => 'badge-status-inactive',
                                    default => 'badge-status-default',
                                };

                                $engagement = [
                                    'interested' => $profile?->investmentRequests?->where('status', 'interested')->count() ?? 0,
                                    'requested'  => $profile?->investmentRequests?->where('status', 'requested')->count() ?? 0,
                                    'approved'   => $profile?->investmentRequests?->where('status', 'approved')->count() ?? 0,
                                    'rejected'   => $profile?->investmentRequests?->where('status', 'rejected')->count() ?? 0,
                                ];
                            @endphp

                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration + ($investors->currentPage() - 1) * $investors->perPage() }}
                                </td>

                                <td>
                                    @if($profile)
                                        <div class="student-name-cell">
                                            <a href="{{ route('admin.investors.show', $profile->user_id) }}" style="text-decoration:none; color:inherit;">
                                                {{ $inv->name }}
                                            </a>
                                        </div>
                                    @else
                                        <div class="student-name-cell">{{ $inv->name }}</div>
                                    @endif

                                    <div class="student-email-cell">{{ $inv->email }}</div>
                                    <div class="mini-text">{{ __('backend.investors_index.username') }}: {{ $inv->username }}</div>
                                </td>

                                <td>
                                    <div class="student-muted-cell">{{ $profile?->company ?? __('backend.investors_index.empty') }}</div>
                                    <div class="mini-text">{{ __('backend.investors_index.position') }}: {{ $profile?->position ?? __('backend.investors_index.empty') }}</div>
                                </td>

                                <td>
                                    <div class="student-muted-cell">{{ $profile?->phone ?? __('backend.investors_index.empty') }}</div>
                                    <div class="mini-text">{{ $inv->city ?? __('backend.investors_index.empty') }}</div>
                                </td>

                                <td>
                                    <div class="student-muted-cell">{{ $profile?->investment_type ?? __('backend.investors_index.empty') }}</div>
                                    <div class="mini-text">{{ __('backend.investors_index.source') }}: {{ $profile?->source ?? __('backend.investors_index.empty') }}</div>
                                </td>

                                <td class="text-center">
                                    <div class="student-muted-cell">
                                        {{ $profile && $profile->budget ? '$'.number_format($profile->budget, 2) : __('backend.investors_index.empty') }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    @if($profile && $profile->trashed())
                                        <span class="badge-soft badge-archived">{{ __('backend.investors_index.archived') }}</span>
                                    @else
                                        <span class="badge-soft {{ $statusClass }}">
                                            {{ ucfirst($inv->status ?? __('backend.investors_index.empty')) }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap gap-1 mb-1">
                                        <span class="badge-soft badge-funding-interested">{{ __('backend.investors_index.engagement_interested') }}: {{ $engagement['interested'] }}</span>
                                        <span class="badge-soft badge-funding-requested">{{ __('backend.investors_index.engagement_requested') }}: {{ $engagement['requested'] }}</span>
                                        <span class="badge-soft badge-funding-approved">{{ __('backend.investors_index.engagement_approved') }}: {{ $engagement['approved'] }}</span>
                                        <span class="badge-soft badge-funding-rejected">{{ __('backend.investors_index.engagement_rejected') }}: {{ $engagement['rejected'] }}</span>
                                    </div>

                                    @if($profile && $profile->investmentRequests->count())
                                        <div class="mini-text">
                                            @foreach($profile->investmentRequests->take(2) as $invItem)
                                                <div>• {{ optional($invItem->project)->name }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="student-muted-cell">{{ $inv->created_at?->format('Y-m-d') }}</div>
                                    <div class="mini-text">{{ $inv->created_at?->format('h:i A') }}</div>
                                </td>

                                <td class="text-center">
                                    <div class="actions-group">
                                        @if($profile)
                                            <a href="{{ route('admin.investors.show', $profile->user_id) }}" class="action-btn btn-view" title="{{ __('backend.investors_index.view') }}">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a href="{{ route('admin.investors.edit', $profile->user_id) }}" class="action-btn btn-edit" title="{{ __('backend.investors_index.edit') }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            @if($profile->trashed())
                                                <form action="{{ route('admin.investors.restore', $profile->user_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="action-btn btn-restore" title="{{ __('backend.investors_index.restore') }}">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.investors.destroy', $profile->user_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn btn-delete" onclick="return confirm('{{ __('backend.investors_index.confirm_delete') }}')" title="{{ __('backend.investors_index.delete') }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="empty-state">{{ __('backend.investors_index.no_investors_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pagination-wrap">
            {{ $investors->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const filterForm = document.getElementById('investorFilterForm');

        document.querySelectorAll('.auto-submit-filter').forEach(el => {
            el.addEventListener('change', function () {
                if (filterForm) filterForm.submit();
            });
        });

        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

        const comparer = (idx, asc) => (a, b) => {
            const v1 = getCellValue(asc ? a : b, idx).trim();
            const v2 = getCellValue(asc ? b : a, idx).trim();

            const n1 = parseFloat(v1.replace(/[^0-9.-]/g, ''));
            const n2 = parseFloat(v2.replace(/[^0-9.-]/g, ''));

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
                const icon = this.querySelector('i');
                if (icon) {
                    icon.className = this.asc ? 'bi bi-caret-up-fill' : 'bi bi-caret-down-fill';
                }
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
@endsection