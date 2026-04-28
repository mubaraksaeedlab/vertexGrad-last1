@extends('layouts.app')

@section('title', __('backend.projects_index.page_title'))

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

    .projects-page {
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

    .stats-grid .col-lg-3,
    .stats-grid .col-md-6,
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

    .stat-card.stat-pending {
        color: #b88900;
        background: linear-gradient(135deg, #ffffff 0%, #fff9eb 100%);
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
        padding: 15px 12px;
        vertical-align: middle;
        white-space: nowrap;
        text-align: center;
    }

    .students-table tbody td {
        border-color: #eef2f7;
        padding: 14px 12px;
        vertical-align: middle;
        font-size: 0.9rem;
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

    .student-muted-cell {
        color: #667085;
        font-size: 0.88rem;
    }

    .project-name-link {
        text-decoration: none;
        color: inherit;
    }

    .project-name-link:hover {
        text-decoration: none;
        color: var(--primary-color);
    }

    .mini-text {
        font-size: 0.78rem;
        color: #667085;
        margin-top: 3px;
        line-height: 1.45;
    }

    .badge-soft {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 10px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .badge-scan-completed {
        background: #ecfdf5;
        color: #15803d;
    }

    .badge-scan-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .badge-scan-failed {
        background: #fef2f2;
        color: #dc2626;
    }

    .badge-status-default {
        background: #f1f5f9;
        color: #475569;
    }

    .badge-final-published {
        background: #dcfce7;
        color: #166534;
    }

    .badge-final-revision {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-final-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-final-pending {
        background: #e2e8f0;
        color: #334155;
    }

    .review-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 24px;
        height: 24px;
        border-radius: 7px;
        font-weight: 800;
        font-size: 0.76rem;
        margin-right: 4px;
    }

    .review-approved {
        background: #dcfce7;
        color: #166534;
    }

    .review-revision {
        background: #fef3c7;
        color: #92400e;
    }

    .review-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .score-box {
        font-weight: 800;
        color: #0f172a;
        font-size: 0.9rem;
    }

    .actions-group {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        transition: all 0.2s ease;
        text-decoration: none;
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

    .btn-approve {
        background: rgba(28, 200, 138, 0.12);
        color: #15803d;
    }

    .btn-publish {
        background: rgba(139, 92, 246, 0.14);
        color: #7c3aed;
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

    .clean-pagination {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .clean-page-link {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 1px solid #dbe4f0;
        background: #fff;
        color: #334155;
        font-weight: 800;
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        transition: all 0.22s ease;
    }

    .clean-page-link:hover {
        text-decoration: none;
        color: var(--primary-color);
        border-color: #c7d2fe;
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .clean-page-link.active {
        background: linear-gradient(135deg, #4e73df, #6f8df3);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 10px 18px rgba(79, 70, 229, 0.25);
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

<div class="container-fluid projects-page">

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show custom-alert mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.projects_index.heading') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.projects_index.subtitle') }}
                </p>
            </div>

            <div>
                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary px-4 py-2 rounded-pill fw-semibold">
                    <i class="fa fa-plus mr-1"></i> {{ __('backend.projects_index.add_project') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row g-3 stats-grid mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stat-card stat-all">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.projects_index.total_projects') }}</p>
                    <span class="stat-icon"><i class="bi bi-folder-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $totalProjects ?? 0 }}</h3>
                <div class="stat-note">{{ __('backend.projects_index.total_projects_note') }}</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stat-card stat-active">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.projects_index.completed_projects') }}</p>
                    <span class="stat-icon"><i class="bi bi-check-circle-fill"></i></span>
                </div>
                <h3 class="stat-value">{{ $completedProjects ?? 0 }}</h3>
                <div class="stat-note">{{ __('backend.projects_index.completed_projects_note') }}</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stat-card stat-pending">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.projects_index.pending_review') }}</p>
                    <span class="stat-icon"><i class="bi bi-hourglass-split"></i></span>
                </div>
                <h3 class="stat-value">{{ $pendingProjects ?? 0 }}</h3>
                <div class="stat-note">{{ __('backend.projects_index.pending_review_note') }}</div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stat-card stat-add">
                <div class="stat-top">
                    <p class="stat-label">{{ __('backend.projects_index.average_scan_score') }}</p>
                    <span class="stat-icon"><i class="bi bi-graph-up-arrow"></i></span>
                </div>
                <h3 class="stat-value">{{ isset($avgScore) && $avgScore !== null ? number_format($avgScore, 1) : '0.0' }}</h3>
                <div class="stat-note">{{ __('backend.projects_index.average_scan_score_note') }}</div>
            </div>
        </div>
    </div>

    <div class="main-panel">
        <div class="panel-head">
            <h2 class="panel-title">{{ __('backend.projects_index.all_projects') }}</h2>
            <div class="panel-subtitle">{{ __('backend.projects_index.all_projects_subtitle') }}</div>
        </div>

        <div class="table-wrap">
            <div class="table-responsive students-table-card">
                <table class="table students-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{ __('backend.projects_index.project') }}</th>
                            <th>{{ __('backend.projects_index.student_team') }}</th>
                            <th class="text-center">{{ __('backend.projects_index.scan') }}</th>
                            <th class="text-center">{{ __('backend.projects_index.score') }}</th>
                            <th class="text-center">{{ __('backend.projects_index.reviews') }}</th>
                            <th class="text-center">{{ __('backend.projects_index.supervisor_avg') }}</th>
                            <th class="text-center">{{ __('backend.projects_index.final_decision') }}</th>
                            <th class="text-center">{{ __('backend.projects_index.budget') }}</th>
                            <th class="text-center">{{ __('backend.projects_index.created') }}</th>
                            <th class="text-center">{{ __('backend.projects_index.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($projects as $project)
                            @php
                                $scanClass = match($project->scanner_status) {
                                    'completed' => 'badge-scan-completed',
                                    'failed' => 'badge-scan-failed',
                                    'pending' => 'badge-scan-pending',
                                    default => 'badge-status-default',
                                };

                                $approvedReviews = $project->reviews->where('decision', 'approved')->count();
                                $revisionReviews = $project->reviews->where('decision', 'revision_requested')->count();
                                $rejectedReviews = $project->reviews->where('decision', 'rejected')->count();
                                $supervisorAvgScore = round($project->reviews->whereNotNull('score')->avg('score') ?? 0, 1);

                                $finalDecisionClass = match($project->final_decision) {
                                    'published' => 'badge-final-published',
                                    'revision_requested' => 'badge-final-revision',
                                    'rejected' => 'badge-final-rejected',
                                    default => 'badge-final-pending',
                                };

                                $finalDecisionText = match($project->final_decision) {
                                    'published' => __('backend.projects_index.final_decision_published'),
                                    'revision_requested' => __('backend.projects_index.final_decision_revision_requested'),
                                    'rejected' => __('backend.projects_index.final_decision_rejected'),
                                    default => __('backend.projects_index.final_decision_pending'),
                                };
                            @endphp

                            <tr>
                                <td class="text-center">{{ $projects->firstItem() + $loop->index }}</td>

                                <td>
                                    <div class="student-name-cell">
                                        <a href="{{ route('admin.projects.show', $project) }}" class="project-name-link">
                                            {{ $project->name ?? __('backend.projects_index.untitled_project') }}
                                        </a>
                                    </div>
                                    <div class="mini-text">#{{ $project->project_id ?? $project->id }}</div>
                                    <div class="mini-text">{{ $project->category ?? __('backend.projects_index.no_category') }}</div>
                                </td>

                                <td>
                                    <div class="student-name-cell">{{ $project->student->name ?? '—' }}</div>
                                    <div class="mini-text">{{ $project->student->email ?? __('backend.projects_index.no_email') }}</div>
                                    <div class="mini-text">{{ $project->supervisor->name ?? __('backend.projects_index.no_supervisor') }}</div>
                                </td>

                                <td class="text-center">
                                    <span class="badge-soft {{ $scanClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->scanner_status ?? __('backend.projects_index.not_scanned'))) }}
                                    </span>
                                    <div class="mini-text">{{ __('backend.projects_index.id_label') }}: {{ $project->scanner_project_id ?? '—' }}</div>
                                </td>

                                <td class="text-center">
                                    <div class="score-box">
                                        {{ $project->scan_score !== null ? number_format($project->scan_score, 2) : '—' }}
                                    </div>
                                    <div class="mini-text">{{ $project->grade ?? __('backend.projects_index.no_grade') }}</div>
                                </td>

                                <td class="text-center">
                                    <span class="review-chip review-approved" title="{{ __('backend.projects_index.approved') }}">{{ $approvedReviews }}</span>
                                    <span class="review-chip review-revision" title="{{ __('backend.projects_index.revision_requested') }}">{{ $revisionReviews }}</span>
                                    <span class="review-chip review-rejected" title="{{ __('backend.projects_index.rejected') }}">{{ $rejectedReviews }}</span>
                                </td>

                                <td class="text-center">
                                    <div class="score-box">
                                        {{ $project->reviews->count() ? number_format($supervisorAvgScore, 1) : '—' }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge-soft {{ $finalDecisionClass }}">
                                        {{ $finalDecisionText }}
                                    </span>
                                    <div class="mini-text">{{ $project->finalDecisionMaker->name ?? '—' }}</div>
                                </td>

                                <td class="text-center">
                                    <div class="student-muted-cell">
                                        {{ $project->budget !== null ? number_format($project->budget, 0) : '—' }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="student-muted-cell">{{ optional($project->created_at)->format('Y-m-d') ?? '—' }}</div>
                                </td>

                                <td class="text-center">
                                    <div class="actions-group">
                                        <a href="{{ route('admin.projects.show', $project) }}" class="action-btn btn-view" title="{{ __('backend.projects_index.view') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.projects.edit', $project) }}" class="action-btn btn-edit" title="{{ __('backend.projects_index.edit') }}">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>

                                        @if(in_array($project->status, ['pending', 'scan_requested', 'awaiting_manual_review']))
                                            <form action="{{ route('admin.projects.approve', $project) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn btn-approve" title="{{ __('backend.projects_index.approve') }}"
                                                    onclick="return confirm('{{ __('backend.projects_index.confirm_approve') }}')">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if(in_array($project->status, ['active', 'approved']))
                                            <form action="{{ route('admin.projects.publish', $project) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn btn-publish" title="{{ __('backend.projects_index.publish') }}"
                                                    onclick="return confirm('{{ __('backend.projects_index.confirm_publish') }}')">
                                                    <i class="fa fa-upload"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn-delete" title="{{ __('backend.projects_index.delete') }}"
                                                onclick="return confirm('{{ __('backend.projects_index.confirm_delete') }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="empty-state">{{ __('backend.projects_index.no_projects_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($projects, 'links') && $projects->lastPage() > 1)
            <div class="pagination-wrap text-center">
                <div class="mini-text mb-3">
                    {{ __('backend.projects_index.showing_results', [
                        'from' => $projects->firstItem() ?? 0,
                        'to' => $projects->lastItem() ?? 0,
                        'total' => $projects->total() ?? 0,
                    ]) }}
                </div>

                <div class="clean-pagination">
                    @for($page = 1; $page <= $projects->lastPage(); $page++)
                        <a href="{{ $projects->url($page) }}"
                           class="clean-page-link {{ $projects->currentPage() === $page ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endfor
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.stat-card, .main-panel, .page-header-card').forEach((card, index) => {
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