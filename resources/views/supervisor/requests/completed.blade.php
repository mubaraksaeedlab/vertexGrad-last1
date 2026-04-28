@extends('supervisor.layout.app_super')

@section('title', __('backend.supervisor_completed_requests.page_title'))

@section('content')
@php
    $totalRequests = $requests->count();
    $completedRequests = $requests->filter(fn($item) => strtolower($item->status ?? '') === 'completed')->count();
    $systemRequests = $requests->filter(fn($item) => strtolower($item->request_type ?? '') === 'system_verification')->count();
    $withDueDate = $requests->filter(fn($item) => !empty($item->due_date))->count();
@endphp

<style>
    .requests-page .page-header-card {
        background: linear-gradient(135deg, #0d1b4c 0%, #1b00ff 100%);
        border-radius: 20px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 12px 30px rgba(27, 0, 255, 0.18);
    }

    .requests-page .page-header-card h3 {
        margin: 0;
        font-weight: 700;
        color: #fff;
    }

    .requests-page .page-header-card p {
        margin: 8px 0 0;
        opacity: 0.9;
    }

    .requests-page .stats-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2ff;
        height: 100%;
        transition: 0.3s ease;
    }

    .requests-page .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.10);
    }

    .requests-page .stats-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 14px;
        color: #fff;
    }

    .requests-page .stats-icon.primary { background: linear-gradient(135deg, #1b00ff, #4f46e5); }
    .requests-page .stats-icon.success { background: linear-gradient(135deg, #16a34a, #22c55e); }
    .requests-page .stats-icon.info { background: linear-gradient(135deg, #0891b2, #06b6d4); }
    .requests-page .stats-icon.warning { background: linear-gradient(135deg, #d97706, #f59e0b); }

    .requests-page .stats-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 8px;
    }

    .requests-page .stats-label {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0;
    }

    .requests-page .table-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .requests-page .table-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .requests-page .table-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .requests-page .modern-table {
        margin-bottom: 0;
        width: 100%;
        table-layout: fixed;
    }

    .requests-page .modern-table thead th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-size: 13px;
    }

    .requests-page .modern-table tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 13px;
        overflow: hidden;
    }

    .requests-page .modern-table tbody tr:hover {
        background: #fafcff;
    }

    .requests-page .col-id { width: 60px; }
    .requests-page .col-project { width: 190px; }
    .requests-page .col-student { width: 150px; }
    .requests-page .col-title { width: 220px; }
    .requests-page .col-type { width: 150px; }
    .requests-page .col-date { width: 120px; }
    .requests-page .col-status { width: 120px; }

    .requests-page .td-ellipsis {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .requests-page .request-title {
        font-weight: 700;
        color: #1e293b;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .requests-page .mini-text {
        font-size: 11px;
        color: #64748b;
        margin-top: 3px;
        line-height: 1.5;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .requests-page .badge-soft {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .requests-page .badge-status-completed {
        background: #ecfdf5;
        color: #15803d;
    }

    .requests-page .badge-status-default {
        background: #f1f5f9;
        color: #475569;
    }

    .requests-page .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .requests-page .btn-outline-header {
        border: 1px solid rgba(255,255,255,.35);
        color: #fff;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 600;
        text-decoration: none;
        background: rgba(255,255,255,.08);
    }

    .requests-page .btn-outline-header:hover {
        color: #fff;
        text-decoration: none;
        background: rgba(255,255,255,.14);
    }

    .requests-page .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #64748b;
    }

    .requests-page .empty-state i {
        font-size: 42px;
        margin-bottom: 12px;
        color: #cbd5e1;
    }

    .requests-page .custom-pagination-wrap {
        padding: 18px 20px 24px;
        border-top: 1px solid #eef2f7;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .requests-page .custom-pagination {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .requests-page .custom-page-item {
        min-width: 42px;
        height: 42px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #334155;
        font-weight: 700;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
        padding: 0 14px;
    }

    .requests-page .custom-page-item:hover {
        text-decoration: none;
        color: #1b00ff;
        border-color: #c7d2fe;
        background: #eef2ff;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(27, 0, 255, 0.10);
    }

    .requests-page .custom-page-item.active {
        background: linear-gradient(135deg, #1b00ff, #4338ca);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(27, 0, 255, 0.22);
    }

    @media (max-width: 1400px) {
        .requests-page .modern-table thead th,
        .requests-page .modern-table tbody td {
            font-size: 12px;
            padding: 10px 8px;
        }
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 requests-page">
    <div class="min-height-200px">

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm" style="border-radius: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-header-card mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                <div>
                    <h3>{{ __('backend.supervisor_completed_requests.heading') }}</h3>
                    <p>{{ __('backend.supervisor_completed_requests.subtitle') }}</p>
                </div>

                <div class="header-actions">
                    <a href="{{ route('supervisor.requests.index') }}" class="btn-outline-header">
                        <i class="fa fa-list mr-1"></i> {{ __('backend.supervisor_completed_requests.all_requests') }}
                    </a>
                    <a href="{{ route('supervisor.dashboard') }}" class="btn-outline-header">
                        <i class="fa fa-home mr-1"></i> {{ __('backend.supervisor_completed_requests.dashboard') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon primary">
                        <i class="fa fa-check-square"></i>
                    </div>
                    <div class="stats-number">{{ $totalRequests }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_completed_requests.completed_requests') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon success">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="stats-number">{{ $completedRequests }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_completed_requests.marked_completed') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon info">
                        <i class="fa fa-cogs"></i>
                    </div>
                    <div class="stats-number">{{ $systemRequests }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_completed_requests.system_verification') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon warning">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div class="stats-number">{{ $withDueDate }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_completed_requests.with_due_date') }}</p>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <div>
                    <h5>{{ __('backend.supervisor_completed_requests.completed_requests_list') }}</h5>
                    <small class="text-muted">{{ __('backend.supervisor_completed_requests.completed_requests_list_subtitle') }}</small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th class="col-id">#</th>
                            <th class="col-project">{{ __('backend.supervisor_completed_requests.project') }}</th>
                            <th class="col-student">{{ __('backend.supervisor_completed_requests.student') }}</th>
                            <th class="col-title">{{ __('backend.supervisor_completed_requests.title') }}</th>
                            <th class="col-type">{{ __('backend.supervisor_completed_requests.type') }}</th>
                            <th class="col-date">{{ __('backend.supervisor_completed_requests.due_date') }}</th>
                            <th class="col-status">{{ __('backend.supervisor_completed_requests.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $requestItem)
                            <tr>
                                <td>{{ $requestItem->id }}</td>

                                <td>
                                    <div class="td-ellipsis">{{ $requestItem->project->name ?? '—' }}</div>
                                    <div class="mini-text td-ellipsis">
                                        {{ __('backend.supervisor_completed_requests.project_id') }} {{ $requestItem->project->project_id ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div class="td-ellipsis">{{ $requestItem->student->name ?? '—' }}</div>
                                    <div class="mini-text td-ellipsis">
                                        {{ $requestItem->student->email ?? __('backend.supervisor_completed_requests.no_email') }}
                                    </div>
                                </td>

                                <td>
                                    <span class="request-title">{{ $requestItem->title }}</span>
                                </td>

                                <td>
                                    <span class="td-ellipsis">
                                        {{ ucfirst(str_replace('_', ' ', $requestItem->request_type)) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $requestItem->due_date ? \Carbon\Carbon::parse($requestItem->due_date)->format('Y-m-d') : '—' }}
                                </td>

                                <td>
                                    <span class="badge-soft badge-status-completed">
                                        {{ ucfirst($requestItem->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa fa-check-square"></i>
                                        <div>{{ __('backend.supervisor_completed_requests.no_completed_requests_found') }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator && $requests->lastPage() > 1)
                <div class="custom-pagination-wrap">
                    <div class="custom-pagination">
                        @for($i = 1; $i <= $requests->lastPage(); $i++)
                            <a href="{{ $requests->url($i) }}"
                               class="custom-page-item {{ $requests->currentPage() == $i ? 'active' : '' }}">
                                {{ $i }}
                            </a>
                        @endfor
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection