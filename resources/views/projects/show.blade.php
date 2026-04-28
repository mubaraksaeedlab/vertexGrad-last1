@extends('layouts.app')
@section('title', __('backend.project_details.page_title'))

@section('content')
@php
    $scanReport = $project->scan_report;

    if (is_string($scanReport)) {
        $decoded = json_decode($scanReport, true);
        $scanReport = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

    $scanSummary = data_get($scanReport, 'summary', []);
    $scanInfo = data_get($scanReport, 'scan', []);
    $scanProject = data_get($scanReport, 'project', []);
    $highlights = data_get($scanReport, 'highlights', []);
    $recommendations = data_get($scanReport, 'recommendations', []);

    $statusClass = match($project->status) {
        'pending', 'scan_requested', 'awaiting_manual_review' => 'warning',
        'active', 'approved', 'published' => 'primary',
        'completed' => 'success',
        'rejected', 'scan_failed', 'failed' => 'danger',
        default => 'secondary',
    };

    $scannerStatusClass = match($project->scanner_status) {
        'completed' => 'success',
        'pending' => 'warning',
        'failed' => 'danger',
        default => 'secondary',
    };

    $riskLevel = $project->risk_level ?? data_get($scanInfo, 'risk_level');
    $riskClass = match(strtolower($riskLevel ?? '')) {
        'low' => 'success',
        'medium' => 'warning',
        'high' => 'danger',
        default => 'secondary',
    };
@endphp

<style>
    :root {
        --page-bg: #f5f7fb;
        --card-bg: #ffffff;
        --text-main: #172033;
        --text-soft: #7b8497;
        --border-color: #e8ecf4;
        --primary-color: #4e73df;
        --shadow-sm: 0 8px 20px rgba(18, 38, 63, 0.06);
        --radius-xl: 24px;
    }

    body { background: var(--page-bg); }

    .project-details-page {
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
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .page-subtitle {
        margin: 10px 0 0;
        color: var(--text-soft);
        line-height: 1.8;
    }

    .hero-badges .badge {
        font-size: 13px;
        padding: 8px 12px;
        border-radius: 999px;
        margin-right: 8px;
        margin-bottom: 8px;
    }

    .info-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        height: 100%;
    }

    .info-card .card-body {
        padding: 22px;
    }

    .mini-stat-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .mini-stat-value {
        color: #0f172a;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.3;
    }

    .section-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .section-card .card-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px;
    }

    .section-card .card-header h3,
    .section-card .card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .detail-grid .item {
        padding: 14px 0;
        border-bottom: 1px dashed #e5e7eb;
    }

    .detail-grid .item:last-child {
        border-bottom: 0;
    }

    .detail-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 15px;
        color: #0f172a;
        font-weight: 600;
        word-break: break-word;
    }

    .summary-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        height: 100%;
    }

    .summary-box h6 {
        font-weight: 700;
        margin-bottom: 12px;
        color: #0f172a;
    }

    .summary-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .summary-list li {
        padding: 8px 0;
        border-bottom: 1px dashed #e5e7eb;
        color: #334155;
    }

    .summary-list li:last-child {
        border-bottom: 0;
    }

    .scan-score-box {
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border-radius: 18px;
        padding: 20px;
        text-align: center;
        border: 1px solid #bfdbfe;
    }

    .scan-score-number {
        font-size: 36px;
        font-weight: 800;
        color: #1d4ed8;
        line-height: 1;
    }

    .scan-score-label {
        margin-top: 8px;
        color: #475569;
        font-weight: 600;
    }

    .highlight-list li,
    .recommend-list li {
        margin-bottom: 10px;
        color: #334155;
    }

    .section-card .table thead th {
        white-space: nowrap;
    }

    .action-btns form {
        display: inline-block;
    }

    @media (max-width: 576px) {
        .page-title { font-size: 1.3rem; }
    }
</style>

<div class="container-fluid project-details-page">

    <div class="page-header-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:16px;">
            <div>
                <h1 class="page-title">{{ $project->name }}</h1>
                <p class="page-subtitle mb-0">{{ $project->description ?? '-' }}</p>
            </div>

            <div class="hero-badges text-md-right">
                <span class="badge badge-{{ $statusClass }}">{{ __('backend.project_details.project_status') }}: {{ ucfirst(str_replace('_', ' ', $project->status ?? 'unknown')) }}</span>
                <span class="badge badge-{{ $scannerStatusClass }}">{{ __('backend.project_details.scan_status') }}: {{ ucfirst(str_replace('_', ' ', $project->scanner_status ?? __('backend.project_details.not_scanned'))) }}</span>
                <span class="badge badge-{{ $riskClass }}">{{ __('backend.project_details.risk') }}: {{ $riskLevel ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card info-card">
                <div class="card-body">
                    <div class="mini-stat-label">{{ __('backend.project_details.scan_score') }}</div>
                    <div class="mini-stat-value">{{ $project->scan_score !== null ? number_format($project->scan_score, 2) : '-' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card info-card">
                <div class="card-body">
                    <div class="mini-stat-label">{{ __('backend.project_details.scanner_project_id') }}</div>
                    <div class="mini-stat-value">{{ $project->scanner_project_id ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card info-card">
                <div class="card-body">
                    <div class="mini-stat-label">{{ __('backend.project_details.scanned_at') }}</div>
                    <div class="mini-stat-value">{{ $project->scanned_at ? \Carbon\Carbon::parse($project->scanned_at)->format('d/m/Y H:i') : '-' }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card info-card">
                <div class="card-body">
                    <div class="mini-stat-label">{{ __('backend.project_details.investors_count') }}</div>
                    <div class="mini-stat-value">{{ $project->investors->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card section-card">
        <div class="card-header">
            <h3>{{ __('backend.project_details.project_overview') }}</h3>
        </div>
        <div class="card-body">
            <div class="row detail-grid">
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.status') }}</div>
                    <div class="detail-value">{{ $project->status ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.progress') }}</div>
                    <div class="detail-value">{{ $project->progress ?? 0 }}%</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.category') }}</div>
                    <div class="detail-value">{{ $project->category ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.budget') }}</div>
                    <div class="detail-value">{{ $project->budget ?? '-' }}</div>
                </div>

                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.priority') }}</div>
                    <div class="detail-value">{{ $project->priority ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.start_date') }}</div>
                    <div class="detail-value">{{ optional($project->start_date)->format('d/m/Y') ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.end_date') }}</div>
                    <div class="detail-value">{{ optional($project->end_date)->format('d/m/Y') ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.created_at') }}</div>
                    <div class="detail-value">{{ optional($project->created_at)->format('d/m/Y H:i') ?? '-' }}</div>
                </div>

                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.scanner_status') }}</div>
                    <div class="detail-value">{{ $project->scanner_status ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.scanner_project_id') }}</div>
                    <div class="detail-value">{{ $project->scanner_project_id ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.scan_score') }}</div>
                    <div class="detail-value">{{ $project->scan_score !== null ? number_format($project->scan_score, 2) : '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.risk_level') }}</div>
                    <div class="detail-value">{{ $riskLevel ?? '-' }}</div>
                </div>
            </div>

            @if($project->status === 'pending')
                <hr class="my-3">
                <div class="d-flex gap-2 action-btns flex-wrap">
                    <form method="POST" action="{{ route('admin.projects.approve', $project) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">{{ __('backend.project_details.approve_project') }}</button>
                    </form>

                    <form method="POST" action="{{ route('admin.projects.reject', $project) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">{{ __('backend.project_details.reject_project') }}</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="card section-card">
        <div class="card-header">
            <h3>{{ __('backend.project_details.people_assignment') }}</h3>
        </div>
        <div class="card-body">
            <div class="row detail-grid">
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.student') }}</div>
                    <div class="detail-value">{{ $project->student?->name ?? '-' }}</div>
                    <div class="text-muted small">{{ $project->student?->email ?? '' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.supervisor') }}</div>
                    <div class="detail-value">{{ $project->supervisor?->name ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.manager') }}</div>
                    <div class="detail-value">{{ $project->manager?->name ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.project_details.total_investors') }}</div>
                    <div class="detail-value">{{ $project->investors->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card section-card">
        <div class="card-header">
            <h3>{{ __('backend.project_details.scan_intelligence_summary') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <div class="scan-score-box">
                        <div class="scan-score-number">{{ $project->scan_score !== null ? number_format($project->scan_score, 0) : '-' }}</div>
                        <div class="scan-score-label">{{ __('backend.project_details.overall_score') }}</div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="summary-box">
                        <h6>{{ __('backend.project_details.scan_metadata') }}</h6>
                        <ul class="summary-list">
                            <li><strong>{{ __('backend.project_details.event') }}:</strong> {{ data_get($scanReport, 'event', '-') }}</li>
                            <li><strong>{{ __('backend.project_details.version') }}:</strong> {{ data_get($scanReport, 'version', '-') }}</li>
                            <li><strong>{{ __('backend.project_details.grade') }}:</strong> {{ data_get($scanInfo, 'grade', $project->grade ?? '-') }}</li>
                            <li><strong>{{ __('backend.project_details.status') }}:</strong> {{ data_get($scanInfo, 'status', $project->scanner_status ?? '-') }}</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="summary-box">
                        <h6>{{ __('backend.project_details.issue_summary') }}</h6>
                        <ul class="summary-list">
                            <li><strong>{{ __('backend.project_details.total_files') }}:</strong> {{ data_get($scanSummary, 'total_files', '-') }}</li>
                            <li><strong>{{ __('backend.project_details.total_issues') }}:</strong> {{ data_get($scanSummary, 'issues_total', '-') }}</li>
                            <li><strong>{{ __('backend.project_details.critical') }}:</strong> {{ data_get($scanSummary, 'critical', '-') }}</li>
                            <li><strong>{{ __('backend.project_details.high') }}:</strong> {{ data_get($scanSummary, 'high', '-') }}</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="summary-box">
                        <h6>{{ __('backend.project_details.more_details') }}</h6>
                        <ul class="summary-list">
                            <li><strong>{{ __('backend.project_details.medium') }}:</strong> {{ data_get($scanSummary, 'medium', '-') }}</li>
                            <li><strong>{{ __('backend.project_details.low') }}:</strong> {{ data_get($scanSummary, 'low', '-') }}</li>
                            <li><strong>{{ __('backend.project_details.language') }}:</strong> {{ data_get($scanProject, 'language', '-') }}</li>
                            <li><strong>{{ __('backend.project_details.scanned_at') }}:</strong> {{ $project->scanned_at ? \Carbon\Carbon::parse($project->scanned_at)->format('d/m/Y H:i') : '-' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5 class="mb-3">{{ __('backend.project_details.highlights') }}</h5>
                    @if(!empty($highlights))
                        <ul class="highlight-list mb-0">
                            @foreach($highlights as $highlight)
                                <li>{{ $highlight }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">{{ __('backend.project_details.no_highlights_available') }}</p>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <h5 class="mb-3">{{ __('backend.project_details.recommendations') }}</h5>
                    @if(!empty($recommendations))
                        <ul class="recommend-list mb-0">
                            @foreach($recommendations as $recommendation)
                                <li>{{ $recommendation }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">{{ __('backend.project_details.no_recommendations_available') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card section-card">
        <div class="card-header">
            <h3 class="mb-0">{{ __('backend.project_details.interested_investors') }}</h3>
        </div>
        <div class="card-body">
            @php
                $interested = $project->investors->where('pivot.status', 'interested');
            @endphp

            @if($interested->count())
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('backend.project_details.investor') }}</th>
                            <th>{{ __('backend.project_details.email') }}</th>
                            <th>{{ __('backend.project_details.status') }}</th>
                            <th>{{ __('backend.project_details.amount') }}</th>
                            <th>{{ __('backend.project_details.expressed') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interested as $investor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $investor->name }}</td>
                                <td>{{ $investor->email }}</td>
                                <td><span class="badge bg-warning text-dark">{{ __('backend.project_details.interested') }}</span></td>
                                <td>{{ $investor->pivot->amount ?? '-' }}</td>
                                <td>{{ optional($investor->pivot->created_at)->format('d M Y H:i') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted mb-0">{{ __('backend.project_details.no_interest_yet') }}</p>
            @endif
        </div>
    </div>

    <div class="card section-card">
        <div class="card-header">
            <h3 class="mb-0">{{ __('backend.project_details.funding_requests') }}</h3>
        </div>
        <div class="card-body">
            @php
                $requests = $project->investors->where('pivot.status', 'requested');
            @endphp

            @if($requests->count())
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('backend.project_details.investor') }}</th>
                            <th>{{ __('backend.project_details.email') }}</th>
                            <th>{{ __('backend.project_details.amount') }}</th>
                            <th>{{ __('backend.project_details.message') }}</th>
                            <th>{{ __('backend.project_details.requested') }}</th>
                            <th width="220">{{ __('backend.project_details.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $investor)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $investor->name }}</td>
                                <td>{{ $investor->email }}</td>
                                <td>${{ number_format($investor->pivot->amount ?? 0, 2) }}</td>
                                <td>{{ $investor->pivot->message ?? '-' }}</td>
                                <td>{{ optional($investor->pivot->created_at)->format('d M Y H:i') ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('admin.projects.investors.approve', ['project' => $project->project_id, 'user' => $investor->id]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                {{ __('backend.project_details.approve') }}
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.projects.investors.reject', ['project' => $project->project_id, 'user' => $investor->id]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                {{ __('backend.project_details.reject') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted mb-0">{{ __('backend.project_details.no_funding_requests_yet') }}</p>
            @endif
        </div>
    </div>

    <div class="card section-card">
        <div class="card-header">
            <h3 class="mb-0">{{ __('backend.project_details.project_media') }}</h3>
        </div>
        <div class="card-body">
            @php
                $images = $project->getMedia('images');
                $videoUrl = $project->getFirstMediaUrl('videos');
            @endphp

            <h5 class="mb-3">{{ __('backend.project_details.images') }} ({{ $images->count() }})</h5>

            @if($images->count())
                <div class="row">
                    @foreach($images as $img)
                        <div class="col-md-3 mb-3">
                            <a href="{{ $img->getUrl() }}" target="_blank" class="d-block">
                                <img src="{{ $img->getUrl() }}" class="img-fluid rounded border" alt="{{ __('backend.project_details.project_image') }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">{{ __('backend.project_details.no_images_uploaded') }}</p>
            @endif

            <hr>

            <h5 class="mb-3">{{ __('backend.project_details.video') }}</h5>

            @if($videoUrl)
                <video class="w-100 rounded border" controls style="max-height:420px;">
                    <source src="{{ $videoUrl }}" type="video/mp4">
                    {{ __('backend.project_details.video_not_supported') }}
                </video>
            @else
                <p class="text-muted">{{ __('backend.project_details.no_video_uploaded') }}</p>
            @endif
        </div>
    </div>

</div>
@endsection