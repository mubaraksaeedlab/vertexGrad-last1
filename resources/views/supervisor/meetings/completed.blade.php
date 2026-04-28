@extends('supervisor.layout.app_super')

@section('title', __('backend.supervisor_completed_meetings.page_title'))

@section('content')
@php
    $totalMeetings = method_exists($meetings, 'total') ? $meetings->total() : $meetings->count();

    $completedCount = collect(method_exists($meetings, 'items') ? $meetings->items() : $meetings)->filter(function ($meeting) {
        return strtolower($meeting->status ?? '') === 'completed';
    })->count();

    $demoCount = collect(method_exists($meetings, 'items') ? $meetings->items() : $meetings)->filter(function ($meeting) {
        return strtolower($meeting->meeting_type ?? '') === 'demo';
    })->count();

    $reviewCount = collect(method_exists($meetings, 'items') ? $meetings->items() : $meetings)->filter(function ($meeting) {
        return strtolower($meeting->meeting_type ?? '') === 'review';
    })->count();

    $vivaCount = collect(method_exists($meetings, 'items') ? $meetings->items() : $meetings)->filter(function ($meeting) {
        return strtolower($meeting->meeting_type ?? '') === 'viva';
    })->count();
@endphp

<style>
    .meetings-page .page-header-card {
        background: linear-gradient(135deg, #0d1b4c 0%, #1b00ff 100%);
        border-radius: 20px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 12px 30px rgba(27, 0, 255, 0.18);
    }

    .meetings-page .page-header-card h3 {
        margin: 0;
        font-weight: 700;
        color: #fff;
    }

    .meetings-page .page-header-card p {
        margin: 8px 0 0;
        opacity: 0.9;
    }

    .meetings-page .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .meetings-page .btn-outline-header {
        border: 1px solid rgba(255,255,255,.35);
        color: #fff;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 600;
        text-decoration: none;
        background: rgba(255,255,255,.08);
        transition: all 0.3s ease;
    }

    .meetings-page .btn-outline-header:hover {
        color: #fff;
        text-decoration: none;
        background: rgba(255,255,255,.14);
    }

    .meetings-page .stats-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2ff;
        height: 100%;
        transition: 0.3s ease;
    }

    .meetings-page .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.10);
    }

    .meetings-page .stats-icon {
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

    .meetings-page .stats-icon.primary { background: linear-gradient(135deg, #1b00ff, #4f46e5); }
    .meetings-page .stats-icon.success { background: linear-gradient(135deg, #059669, #10b981); }
    .meetings-page .stats-icon.warning { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .meetings-page .stats-icon.info { background: linear-gradient(135deg, #0891b2, #06b6d4); }

    .meetings-page .stats-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 8px;
    }

    .meetings-page .stats-label {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0;
    }

    .meetings-page .table-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .meetings-page .table-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .meetings-page .table-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .meetings-page .modern-table {
        margin-bottom: 0;
        width: 100%;
        table-layout: fixed;
    }

    .meetings-page .modern-table thead th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-size: 13px;
    }

    .meetings-page .modern-table tbody td {
        padding: 12px 10px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 13px;
        overflow: hidden;
    }

    .meetings-page .modern-table tbody tr:hover {
        background: #fafcff;
    }

    .meetings-page .col-id { width: 55px; }
    .meetings-page .col-project { width: 180px; }
    .meetings-page .col-title { width: 220px; }
    .meetings-page .col-type { width: 120px; }
    .meetings-page .col-date { width: 120px; }
    .meetings-page .col-time { width: 120px; }
    .meetings-page .col-status { width: 120px; }
    .meetings-page .col-actions { width: 170px; }

    .meetings-page .meeting-title {
        font-weight: 700;
        color: #1e293b;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .meetings-page .project-name {
        font-weight: 700;
        color: #1e293b;
        text-decoration: none;
        display: block;
        max-width: 165px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .meetings-page .project-name:hover {
        color: #1b00ff;
        text-decoration: none;
    }

    .meetings-page .mini-text {
        font-size: 11px;
        color: #64748b;
        margin-top: 3px;
        line-height: 1.5;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .meetings-page .badge-soft {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .meetings-page .badge-type-demo {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .meetings-page .badge-type-review {
        background: #f5f3ff;
        color: #7c3aed;
    }

    .meetings-page .badge-type-viva {
        background: #ecfeff;
        color: #0891b2;
    }

    .meetings-page .badge-type-discussion {
        background: #f8fafc;
        color: #475569;
    }

    .meetings-page .badge-status-completed {
        background: #ecfdf5;
        color: #15803d;
    }

    .meetings-page .badge-status-default {
        background: #f1f5f9;
        color: #475569;
    }

    .meetings-page .btn-open {
        background: linear-gradient(135deg, #1b00ff, #4338ca);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 7px 14px;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .meetings-page .btn-open:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(27, 0, 255, 0.20);
        text-decoration: none;
    }

    .meetings-page .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #64748b;
    }

    .meetings-page .empty-state i {
        font-size: 42px;
        margin-bottom: 12px;
        color: #cbd5e1;
    }

    @media (max-width: 1400px) {
        .meetings-page .modern-table thead th,
        .meetings-page .modern-table tbody td {
            font-size: 12px;
            padding: 10px 8px;
        }

        .meetings-page .project-name {
            max-width: 145px;
        }
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 meetings-page">
    <div class="min-height-200px">

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm" style="border-radius: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-header-card mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                <div>
                    <h3>{{ __('backend.supervisor_completed_meetings.heading') }}</h3>
                    <p>{{ __('backend.supervisor_completed_meetings.subtitle') }}</p>
                </div>

                <div class="header-actions">
                    <a href="{{ route('supervisor.meetings.index') }}" class="btn-outline-header">
                        <i class="fa fa-calendar mr-1"></i> {{ __('backend.supervisor_completed_meetings.all_meetings') }}
                    </a>
                    <a href="{{ route('supervisor.meetings.create') }}" class="btn-outline-header">
                        <i class="fa fa-plus mr-1"></i> {{ __('backend.supervisor_completed_meetings.create_meeting') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon primary">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div class="stats-number">{{ $totalMeetings }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_completed_meetings.total_completed') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon success">
                        <i class="fa fa-desktop"></i>
                    </div>
                    <div class="stats-number">{{ $demoCount }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_completed_meetings.demo_meetings') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon warning">
                        <i class="fa fa-search"></i>
                    </div>
                    <div class="stats-number">{{ $reviewCount }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_completed_meetings.review_sessions') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon info">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <div class="stats-number">{{ $vivaCount }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_completed_meetings.viva_meetings') }}</p>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <div>
                    <h5>{{ __('backend.supervisor_completed_meetings.finished_meeting_sessions') }}</h5>
                    <small class="text-muted">{{ __('backend.supervisor_completed_meetings.finished_meeting_sessions_subtitle') }}</small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th class="col-id">#</th>
                            <th class="col-project">{{ __('backend.supervisor_completed_meetings.project') }}</th>
                            <th class="col-title">{{ __('backend.supervisor_completed_meetings.meeting_title') }}</th>
                            <th class="col-type">{{ __('backend.supervisor_completed_meetings.type') }}</th>
                            <th class="col-date">{{ __('backend.supervisor_completed_meetings.date') }}</th>
                            <th class="col-time">{{ __('backend.supervisor_completed_meetings.time') }}</th>
                            <th class="col-status">{{ __('backend.supervisor_completed_meetings.status') }}</th>
                            <th class="col-actions">{{ __('backend.supervisor_completed_meetings.action') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($meetings as $meeting)
                            @php
                                $typeClass = match(strtolower($meeting->meeting_type ?? 'discussion')) {
                                    'demo' => 'badge-type-demo',
                                    'review' => 'badge-type-review',
                                    'viva' => 'badge-type-viva',
                                    'discussion' => 'badge-type-discussion',
                                    default => 'badge-status-default',
                                };
                            @endphp

                            <tr>
                                <td>{{ $meeting->id }}</td>

                                <td>
                                    <a href="{{ route('supervisor.projects.show', $meeting->project_id) }}" class="project-name">
                                        {{ $meeting->project->name ?? __('backend.supervisor_completed_meetings.untitled_project') }}
                                    </a>
                                    <div class="mini-text">
                                        {{ __('backend.supervisor_completed_meetings.project_id') }} {{ $meeting->project_id }}
                                    </div>
                                </td>

                                <td>
                                    <span class="meeting-title">{{ $meeting->title ?? __('backend.supervisor_completed_meetings.untitled_meeting') }}</span>
                                    <div class="mini-text">
                                        {{ $meeting->notes ? \Illuminate\Support\Str::limit($meeting->notes, 45) : __('backend.supervisor_completed_meetings.no_notes_added') }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge-soft {{ $typeClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $meeting->meeting_type ?? 'discussion')) }}
                                    </span>
                                </td>

                                <td>
                                    <div>{{ $meeting->meeting_date ? \Carbon\Carbon::parse($meeting->meeting_date)->format('Y-m-d') : '—' }}</div>
                                </td>

                                <td>
                                    <div>{{ $meeting->meeting_time ? \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') : '—' }}</div>
                                </td>

                                <td>
                                    <span class="badge-soft badge-status-completed">
                                        {{ __('backend.supervisor_completed_meetings.completed') }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('supervisor.projects.show', $meeting->project_id) }}" class="btn-open">
                                        <i class="fa fa-folder-open mr-1"></i> {{ __('backend.supervisor_completed_meetings.open_project') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fa fa-check-circle-o"></i>
                                        <div>{{ __('backend.supervisor_completed_meetings.no_completed_meetings_found') }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($meetings, 'links'))
                <div class="p-3">
                    {{ $meetings->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection