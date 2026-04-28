@extends('supervisor.layout.app_super')

@section('title', __('backend.supervisor_all_projects.page_title'))

@section('content')
@php
    $totalProjects = $projects->count();

    $approvedProjects = $projects->filter(function ($project) {
        $myReview = $project->reviews->first();
        return strtolower($myReview->decision ?? '') === 'approved';
    })->count();

    $pendingProjects = $projects->filter(function ($project) {
        $myReview = $project->reviews->first();
        return !$myReview || in_array(strtolower($myReview->decision ?? ''), ['pending', 'under_review']);
    })->count();

    $avgScore = $projects->map(function ($project) {
        return optional($project->reviews->first())->score;
    })->filter(fn ($score) => !is_null($score))->avg();
@endphp

<style>
    .projects-page .page-header-card {
        background: linear-gradient(135deg, #0d1b4c 0%, #1b00ff 100%);
        border-radius: 20px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 12px 30px rgba(27, 0, 255, 0.18);
    }

    .projects-page .page-header-card h3 {
        margin: 0;
        font-weight: 700;
        color: #fff;
    }

    .projects-page .page-header-card p {
        margin: 8px 0 0;
        opacity: 0.9;
    }

    .projects-page .stats-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2ff;
        height: 100%;
        transition: 0.3s ease;
    }

    .projects-page .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.10);
    }

    .projects-page .stats-icon {
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

    .projects-page .stats-icon.primary { background: linear-gradient(135deg, #1b00ff, #4f46e5); }
    .projects-page .stats-icon.success { background: linear-gradient(135deg, #16a34a, #22c55e); }
    .projects-page .stats-icon.warning { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .projects-page .stats-icon.info { background: linear-gradient(135deg, #0891b2, #06b6d4); }

    .projects-page .stats-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 8px;
    }

    .projects-page .stats-label {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0;
    }

    .projects-page .table-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .projects-page .table-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .projects-page .table-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .projects-page .table-wrap {
        width: 100%;
        overflow: hidden;
    }

    .projects-page .modern-table {
        margin-bottom: 0;
        width: 100%;
        table-layout: auto;
    }

    .projects-page .modern-table thead th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 8px;
        vertical-align: middle;
        white-space: nowrap;
        font-size: 12px;
    }

    .projects-page .modern-table tbody td {
        padding: 12px 8px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 12px;
    }

    .projects-page .modern-table tbody tr:hover {
        background: #fafcff;
    }

    .projects-page .col-id { width: 50px; }
    .projects-page .col-project { width: 17%; }
    .projects-page .col-student { width: 13%; }
    .projects-page .col-status { width: 12%; }
    .projects-page .col-scan { width: 10%; }
    .projects-page .col-score { width: 8%; }
    .projects-page .col-review { width: 10%; }
    .projects-page .col-note { width: 15%; }
    .projects-page .col-date { width: 8%; }
    .projects-page .col-actions { width: 7%; }

    .projects-page .project-name {
        font-weight: 700;
        color: #1e293b;
        text-decoration: none;
        display: block;
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .projects-page .project-name:hover {
        color: #1b00ff;
        text-decoration: none;
    }

    .projects-page .td-ellipsis {
        display: block;
        max-width: 170px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .projects-page .mini-text {
        font-size: 10px;
        color: #64748b;
        margin-top: 3px;
        line-height: 1.4;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .projects-page .badge-soft {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .projects-page .badge-status-pending,
    .projects-page .badge-status-scan_requested,
    .projects-page .badge-status-awaiting_manual_review {
        background: #fff7ed;
        color: #c2410c;
    }

    .projects-page .badge-status-awaiting_final_decision {
        background: #ede9fe;
        color: #6d28d9;
    }

    .projects-page .badge-status-active,
    .projects-page .badge-status-approved,
    .projects-page .badge-status-published {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .projects-page .badge-status-completed {
        background: #ecfdf5;
        color: #15803d;
    }

    .projects-page .badge-status-rejected,
    .projects-page .badge-status-scan_failed,
    .projects-page .badge-status-failed {
        background: #fef2f2;
        color: #dc2626;
    }

    .projects-page .badge-status-default {
        background: #f1f5f9;
        color: #475569;
    }

    .projects-page .badge-scan-completed {
        background: #ecfdf5;
        color: #15803d;
    }

    .projects-page .badge-scan-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .projects-page .badge-scan-failed {
        background: #fef2f2;
        color: #dc2626;
    }

    .projects-page .badge-review-approved {
        background: #ecfdf5;
        color: #15803d;
    }

    .projects-page .badge-review-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .projects-page .badge-review-revision_requested {
        background: #fef3c7;
        color: #b45309;
    }

    .projects-page .badge-review-rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    .projects-page .score-box {
        font-weight: 800;
        color: #0f172a;
    }

    .projects-page .btn-review {
        background: linear-gradient(135deg, #1b00ff, #4338ca);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 7px 12px;
        font-weight: 600;
        font-size: 11px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        white-space: nowrap;
    }

    .projects-page .btn-review:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(27, 0, 255, 0.20);
        text-decoration: none;
    }

    .projects-page .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .projects-page .btn-outline-header {
        border: 1px solid rgba(255,255,255,.35);
        color: #fff;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 600;
        text-decoration: none;
        background: rgba(255,255,255,.08);
    }

    .projects-page .btn-outline-header:hover {
        color: #fff;
        text-decoration: none;
        background: rgba(255,255,255,.14);
    }

    .projects-page .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #64748b;
    }

    .projects-page .empty-state i {
        font-size: 42px;
        margin-bottom: 12px;
        color: #cbd5e1;
    }

    .projects-page .custom-pagination-wrap {
        padding: 18px 20px 24px;
        border-top: 1px solid #eef2f7;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .projects-page .custom-pagination {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .projects-page .custom-page-item {
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

    .projects-page .custom-page-item:hover {
        text-decoration: none;
        color: #1b00ff;
        border-color: #c7d2fe;
        background: #eef2ff;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(27, 0, 255, 0.10);
    }

    .projects-page .custom-page-item.active {
        background: linear-gradient(135deg, #1b00ff, #4338ca);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 12px 24px rgba(27, 0, 255, 0.22);
    }

    @media (max-width: 1200px) {
        .projects-page .modern-table {
            table-layout: fixed;
        }

        .projects-page .modern-table thead th,
        .projects-page .modern-table tbody td {
            font-size: 11px;
            padding: 10px 6px;
        }

        .projects-page .project-name,
        .projects-page .td-ellipsis {
            max-width: 120px;
        }

        .projects-page .mini-text {
            font-size: 9px;
        }

        .projects-page .btn-review {
            padding: 6px 10px;
            font-size: 10px;
        }
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 projects-page">
    <div class="min-height-200px">

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm" style="border-radius: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="page-header-card mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                <div>
                    <h3>{{ __('backend.supervisor_all_projects.heading') }}</h3>
                    <p>{{ __('backend.supervisor_all_projects.subtitle') }}</p>
                </div>

                <div class="header-actions">
                    <a href="{{ route('supervisor.projects.pending') }}" class="btn-outline-header">
                        <i class="fa fa-clock mr-1"></i> {{ __('backend.supervisor_all_projects.pending_reviews') }}
                    </a>
                    <a href="{{ route('supervisor.dashboard') }}" class="btn-outline-header">
                        <i class="fa fa-home mr-1"></i> {{ __('backend.supervisor_all_projects.dashboard') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon primary">
                        <i class="fa fa-folder-open"></i>
                    </div>
                    <div class="stats-number">{{ $totalProjects }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_all_projects.total_projects') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon success">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="stats-number">{{ $approvedProjects }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_all_projects.my_approved_evaluations') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon warning">
                        <i class="fa fa-clock"></i>
                    </div>
                    <div class="stats-number">{{ $pendingProjects }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_all_projects.not_evaluated_yet') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon info">
                        <i class="fa fa-chart-line"></i>
                    </div>
                    <div class="stats-number">{{ $avgScore ? number_format($avgScore, 1) : '0.0' }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_all_projects.my_average_score') }}</p>
                </div>
            </div>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <div>
                    <h5>{{ __('backend.supervisor_all_projects.table_title') }}</h5>
                    <small class="text-muted">{{ __('backend.supervisor_all_projects.table_subtitle') }}</small>
                </div>
            </div>

            <div class="table-wrap">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th class="col-id">{{ __('backend.supervisor_all_projects.table_number') }}</th>
                            <th class="col-project">{{ __('backend.supervisor_all_projects.project') }}</th>
                            <th class="col-student">{{ __('backend.supervisor_all_projects.student') }}</th>
                            <th class="col-status">{{ __('backend.supervisor_all_projects.project_status') }}</th>
                            <th class="col-scan">{{ __('backend.supervisor_all_projects.scan') }}</th>
                            <th class="col-score">{{ __('backend.supervisor_all_projects.score') }}</th>
                            <th class="col-review">{{ __('backend.supervisor_all_projects.my_evaluation') }}</th>
                            <th class="col-note">{{ __('backend.supervisor_all_projects.my_notes') }}</th>
                            <th class="col-date">{{ __('backend.supervisor_all_projects.updated') }}</th>
                            <th class="col-actions">{{ __('backend.supervisor_all_projects.action') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($projects as $project)
                            @php
                                $myReview = $project->reviews->first();

                                $displayStatus = strtolower($project->status ?? 'unknown');

                                if ($myReview && $displayStatus === 'awaiting_manual_review') {
                                    $displayStatus = 'awaiting_final_decision';
                                }

                                $statusClass = match($displayStatus) {
                                    'pending', 'scan_requested', 'awaiting_manual_review' => 'badge-status-pending',
                                    'awaiting_final_decision' => 'badge-status-awaiting_final_decision',
                                    'active', 'approved', 'published' => 'badge-status-active',
                                    'completed' => 'badge-status-completed',
                                    'rejected', 'scan_failed', 'failed' => 'badge-status-rejected',
                                    default => 'badge-status-default',
                                };

                                $scanClass = match(strtolower($project->scanner_status ?? '')) {
                                    'completed' => 'badge-scan-completed',
                                    'failed' => 'badge-scan-failed',
                                    'pending' => 'badge-scan-pending',
                                    default => 'badge-status-default',
                                };

                                $myDecision = strtolower($myReview->decision ?? 'pending');

                                $reviewClass = match($myDecision) {
                                    'approved' => 'badge-review-approved',
                                    'revision_requested' => 'badge-review-revision_requested',
                                    'rejected' => 'badge-review-rejected',
                                    default => 'badge-review-pending',
                                };
                            @endphp

                            <tr>
                                <td>{{ $project->project_id }}</td>

                                <td>
                                    <a href="{{ route('supervisor.projects.show', $project->project_id) }}" class="project-name">
                                        {{ $project->name ?? __('backend.supervisor_all_projects.untitled_project') }}
                                    </a>
                                    <div class="mini-text td-ellipsis">
                                        {{ __('backend.supervisor_all_projects.id_label') }}: {{ $project->project_id }}
                                    </div>
                                    <div class="mini-text td-ellipsis">
                                        {{ $project->category ?? __('backend.supervisor_all_projects.no_category') }}
                                    </div>
                                </td>

                                <td>
                                    <div class="td-ellipsis">{{ $project->student->name ?? __('backend.supervisor_all_projects.empty_value') }}</div>
                                    <div class="mini-text td-ellipsis">{{ $project->student->email ?? __('backend.supervisor_all_projects.no_email') }}</div>
                                </td>

                                <td>
                                    <span class="badge-soft {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $displayStatus)) }}
                                    </span>
                                    <div class="mini-text td-ellipsis">
                                        {{ __('backend.supervisor_all_projects.budget') }}:
                                        {{ $project->budget !== null ? number_format($project->budget, 2) : __('backend.supervisor_all_projects.empty_value') }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge-soft {{ $scanClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->scanner_status ?? __('backend.supervisor_all_projects.not_scanned'))) }}
                                    </span>
                                    <div class="mini-text td-ellipsis">
                                        {{ __('backend.supervisor_all_projects.scanner_id') }}:
                                        {{ $project->scanner_project_id ?? __('backend.supervisor_all_projects.empty_value') }}
                                    </div>
                                </td>

                                <td>
                                    <div class="score-box">
                                        {{ $myReview?->score !== null ? number_format($myReview->score, 2) : __('backend.supervisor_all_projects.empty_value') }}
                                    </div>
                                    <div class="mini-text td-ellipsis">
                                        {{ __('backend.supervisor_all_projects.scan_score_label') }}:
                                        {{ $project->scan_score !== null ? number_format($project->scan_score, 2) : __('backend.supervisor_all_projects.empty_value') }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge-soft {{ $reviewClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $myReview->decision ?? 'pending')) }}
                                    </span>
                                    <div class="mini-text td-ellipsis">
                                        {{ $myReview?->reviewed_at ? $myReview->reviewed_at->format('Y-m-d') : __('backend.supervisor_all_projects.not_reviewed_yet') }}
                                    </div>
                                </td>

                                <td>
                                    <div class="td-ellipsis">
                                        {{ $myReview?->notes ? \Illuminate\Support\Str::limit($myReview->notes, 40) : __('backend.supervisor_all_projects.no_notes_yet') }}
                                    </div>
                                </td>

                                <td>
                                    <div>{{ optional($project->updated_at)->format('Y-m-d') ?? __('backend.supervisor_all_projects.empty_value') }}</div>
                                    <div class="mini-text">{{ optional($project->updated_at)->format('h:i A') ?? '' }}</div>
                                </td>

                                <td>
                                    <a href="{{ route('supervisor.projects.show', $project->project_id) }}" class="btn-review">
                                        <i class="fa fa-search mr-1"></i>
                                        {{ $myReview ? __('backend.supervisor_all_projects.update_review') : __('backend.supervisor_all_projects.review') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10">
                                    <div class="empty-state">
                                        <i class="fa fa-folder-open"></i>
                                        <div>{{ __('backend.supervisor_all_projects.no_projects_found') }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($projects instanceof \Illuminate\Pagination\LengthAwarePaginator && $projects->lastPage() > 1)
                <div class="custom-pagination-wrap">
                    <div class="custom-pagination">
                        @for($i = 1; $i <= $projects->lastPage(); $i++)
                            <a href="{{ $projects->url($i) }}"
                               class="custom-page-item {{ $projects->currentPage() == $i ? 'active' : '' }}">
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