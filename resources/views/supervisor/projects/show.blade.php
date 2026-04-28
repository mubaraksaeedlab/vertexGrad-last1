@extends('supervisor.layout.app_super')
@section('title', __('backend.supervisor_project_details.page_title'))

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

    $meetings = $project->meetings ?? collect();

    $projectRequests = method_exists($project, 'requests')
        ? $project->requests()->with(['latestResponse', 'student'])->latest()->get()
        : collect();

    $latestSystemRequest = $projectRequests->first(function ($item) {
        return strtolower($item->request_type ?? '') === 'system_verification'
            && $item->latestResponse;
    });

    $parsedSystemResponse = [
        'frontend_url'     => null,
        'backend_url'      => null,
        'api_health_url'   => null,
        'admin_panel_url'  => null,
        'demo_account'     => null,
        'demo_password'    => null,
        'deployment_notes' => null,
    ];

    if ($latestSystemRequest && !empty($latestSystemRequest->latestResponse?->response_text)) {
        $responseText = $latestSystemRequest->latestResponse->response_text;

        if (preg_match('/Frontend URL:\s*(.+)/i', $responseText, $m)) {
            $parsedSystemResponse['frontend_url'] = trim($m[1]);
        }

        if (preg_match('/Backend URL:\s*(.+)/i', $responseText, $m)) {
            $parsedSystemResponse['backend_url'] = trim($m[1]);
        }

        if (preg_match('/API Health \/ API Base URL:\s*(.+)/i', $responseText, $m)) {
            $parsedSystemResponse['api_health_url'] = trim($m[1]);
        } elseif (preg_match('/API Health URL:\s*(.+)/i', $responseText, $m)) {
            $parsedSystemResponse['api_health_url'] = trim($m[1]);
        } elseif (preg_match('/API URL:\s*(.+)/i', $responseText, $m)) {
            $parsedSystemResponse['api_health_url'] = trim($m[1]);
        }

        if (preg_match('/Admin Panel URL:\s*(.+)/i', $responseText, $m)) {
            $parsedSystemResponse['admin_panel_url'] = trim($m[1]);
        }

        if (preg_match('/Demo Account:\s*(.+)/i', $responseText, $m)) {
            $parsedSystemResponse['demo_account'] = trim($m[1]);
        }

        if (preg_match('/Demo Password:\s*(.+)/i', $responseText, $m)) {
            $parsedSystemResponse['demo_password'] = trim($m[1]);
        }

        if (preg_match('/Deployment Notes:\s*([\s\S]+)/i', $responseText, $m)) {
            $parsedSystemResponse['deployment_notes'] = trim($m[1]);
        }
    }

    $finalFrontendUrl = old('frontend_url', $project->frontend_url ?: $parsedSystemResponse['frontend_url']);
    $finalBackendUrl = old('backend_url', $project->backend_url ?: $parsedSystemResponse['backend_url']);
    $finalApiHealthUrl = old('api_health_url', $project->api_health_url ?: $parsedSystemResponse['api_health_url']);
    $finalAdminPanelUrl = old('admin_panel_url', $project->admin_panel_url ?: $parsedSystemResponse['admin_panel_url']);
    $finalDemoAccount = old('demo_account', $project->demo_account ?: $parsedSystemResponse['demo_account']);
    $finalDemoPassword = old('demo_password', $project->demo_password ?: $parsedSystemResponse['demo_password']);
    $finalDeploymentNotes = old('deployment_notes', $project->deployment_notes ?: $parsedSystemResponse['deployment_notes']);

    $user = auth()->user();
    $canSystemVerification = $user && $user->hasPermission('manage_system_verification');
    $canEvaluateProjects   = $user && $user->hasPermission('evaluate_projects');
    $canViewRequests       = $user && $user->hasPermission('view_requests');
    $canManageRequests     = $user && $user->hasPermission('manage_requests');
    $canViewMeetings       = $user && $user->hasPermission('view_meetings');
    $canManageMeetings     = $user && $user->hasPermission('manage_meetings');
@endphp

<style>
.project-details-page .section-card {
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(0,0,0,.06);
    margin-bottom: 20px;
    border: none;
    overflow: hidden;
}
.project-details-page .hero-card {
    border-radius: 20px;
    background: linear-gradient(135deg,#0f172a,#1d4ed8);
    color:#fff;
    border: none;
}
.project-details-page .hero-title {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 10px;
}
.project-details-page .hero-desc {
    color: rgba(255,255,255,.90);
    max-width: 850px;
    line-height: 1.8;
}
.project-details-page .badge {
    padding: 7px 12px;
    border-radius: 999px;
}
.project-details-page .mini-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);
    padding: 20px;
    height: 100%;
}
.project-details-page .mini-label {
    color: #64748b;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 6px;
}
.project-details-page .mini-value {
    color: #0f172a;
    font-size: 22px;
    font-weight: 800;
}
.project-details-page .section-card .card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 16px 20px;
}
.project-details-page .section-card .card-header h4,
.project-details-page .section-card .card-header h5 {
    margin: 0;
    font-weight: 800;
    color: #0f172a;
}
.project-details-page .verification-box,
.project-details-page .request-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
    height: 100%;
}
.project-details-page .verification-box label,
.project-details-page .request-box label {
    font-weight: 700;
    font-size: 13px;
    color: #475569;
    margin-bottom: 8px;
    display: block;
}
.project-details-page .verification-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 16px;
}
.project-details-page .verification-btn {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
    padding: 9px 14px;
    border-radius: 12px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
}
.project-details-page .verification-btn:hover {
    background: #dbeafe;
    color: #1d4ed8;
    text-decoration: none;
}
.project-details-page .summary-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px;
    height: 100%;
}
.project-details-page .summary-box h6 {
    font-weight: 700;
    margin-bottom: 12px;
    color: #0f172a;
}
.project-details-page .summary-list {
    list-style: none;
    padding: 0;
    margin: 0;
}
.project-details-page .summary-list li {
    padding: 8px 0;
    border-bottom: 1px dashed #e5e7eb;
    color: #334155;
}
.project-details-page .summary-list li:last-child {
    border-bottom: 0;
}
.project-details-page .scan-score-box {
    background: linear-gradient(135deg, #eff6ff, #dbeafe);
    border-radius: 18px;
    padding: 20px;
    text-align: center;
    border: 1px solid #bfdbfe;
}
.project-details-page .scan-score-number {
    font-size: 36px;
    font-weight: 800;
    color: #1d4ed8;
    line-height: 1;
}
.project-details-page .scan-score-label {
    margin-top: 8px;
    color: #475569;
    font-weight: 600;
}
.project-details-page .highlight-list li,
.project-details-page .recommend-list li {
    margin-bottom: 10px;
    color: #334155;
}
.project-details-page .meeting-item,
.project-details-page .request-item {
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 12px;
    background: #fff;
}
.project-details-page .meeting-title,
.project-details-page .request-title {
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 6px;
}
.project-details-page .meeting-meta,
.project-details-page .request-meta {
    color: #64748b;
    font-size: 13px;
    margin-bottom: 10px;
}
.project-details-page .meeting-actions,
.project-details-page .request-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.project-details-page .detail-grid .item {
    padding: 14px 0;
    border-bottom: 1px dashed #e5e7eb;
}
.project-details-page .detail-grid .item:last-child {
    border-bottom: 0;
}
.project-details-page .detail-label {
    font-size: 13px;
    color: #64748b;
    font-weight: 700;
    margin-bottom: 4px;
}
.project-details-page .detail-value {
    font-size: 15px;
    color: #0f172a;
    font-weight: 600;
    word-break: break-word;
}
.project-details-page .table thead th {
    white-space: nowrap;
}
.project-details-page .student-response-box {
    margin-top: 14px;
    background: #f8fafc;
    border: 1px solid #dbeafe;
    border-radius: 14px;
    padding: 16px;
}
.project-details-page .student-response-title {
    font-size: 13px;
    font-weight: 800;
    color: #1d4ed8;
    text-transform: uppercase;
    letter-spacing: .08em;
    margin-bottom: 10px;
}
.project-details-page .student-response-meta {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 10px;
}
.project-details-page .student-response-text {
    color: #334155;
    margin-bottom: 10px;
    white-space: pre-line;
}
.project-details-page .student-response-links {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.project-details-page .response-link-btn {
    background: #ecfeff;
    color: #0f766e;
    border: 1px solid #a5f3fc;
    padding: 8px 12px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    font-size: 13px;
}
.project-details-page .response-link-btn:hover {
    text-decoration: none;
    color: #115e59;
    background: #cffafe;
}
.project-details-page .request-template-bar {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}
.project-details-page .template-btn {
    border: 1px solid #c7d2fe;
    background: #eef2ff;
    color: #3730a3;
    padding: 10px 14px;
    border-radius: 12px;
    font-weight: 800;
    font-size: 13px;
    transition: .2s ease;
}
.project-details-page .template-btn:hover {
    background: #e0e7ff;
}
.project-details-page .template-btn.system {
    background: #ecfeff;
    border-color: #a5f3fc;
    color: #155e75;
}
.project-details-page .template-btn.system:hover {
    background: #cffafe;
}
.project-details-page .template-helper {
    background: #f8fafc;
    border: 1px dashed #cbd5e1;
    border-radius: 14px;
    padding: 14px 16px;
    color: #475569;
    font-size: 13px;
    margin-bottom: 18px;
}
.project-details-page .system-sync-alert {
    background: linear-gradient(135deg, #ecfeff, #eff6ff);
    border: 1px solid #bae6fd;
    color: #0f172a;
    border-radius: 14px;
    padding: 14px 16px;
    margin-bottom: 18px;
}
.project-details-page .system-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
    margin-top: 18px;
}
.project-details-page .system-info-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 14px;
}
.project-details-page .system-info-label {
    font-size: 12px;
    font-weight: 800;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 6px;
}
.project-details-page .system-info-value {
    font-size: 14px;
    color: #0f172a;
    font-weight: 600;
    word-break: break-word;
    white-space: pre-line;
}
.project-details-page .response-toggle-btn {
    width: 100%;
    margin-top: 14px;
    border: 1px solid #dbeafe;
    background: #f8fbff;
    color: #1d4ed8;
    border-radius: 12px;
    padding: 12px 14px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: .2s ease;
}
.project-details-page .response-toggle-btn:hover {
    background: #eef6ff;
}
.project-details-page .response-toggle-icon {
    transition: transform .25s ease;
}
.project-details-page .response-toggle-btn.active .response-toggle-icon {
    transform: rotate(180deg);
}
.project-details-page .response-collapse {
    display: none;
}
.project-details-page .response-collapse.show {
    display: block;
}
.project-details-page .badge-soft {
    display: inline-flex;
    align-items: center;
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 800;
}
.project-details-page .badge-review-approved {
    background: #dcfce7;
    color: #166534;
}
.project-details-page .badge-review-revision_requested {
    background: #fef3c7;
    color: #92400e;
}
.project-details-page .badge-review-rejected {
    background: #fee2e2;
    color: #991b1b;
}
.project-details-page .badge-review-pending {
    background: #e5e7eb;
    color: #374151;
}
@media (max-width: 768px) {
    .project-details-page .system-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="container project-details-page">

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 14px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 14px;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HERO --}}
    <div class="card hero-card p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
            <div>
                <h1 class="hero-title">{{ $project->name }}</h1>
                <p class="hero-desc mb-0">{{ $project->description ?? '-' }}</p>
            </div>

            <div class="text-md-right">
                <span class="badge badge-{{ $statusClass }}">{{ __('backend.supervisor_project_details.project_badge') }}: {{ ucfirst(str_replace('_', ' ', $project->status ?? 'unknown')) }}</span>
                <span class="badge badge-{{ $scannerStatusClass }}">{{ __('backend.supervisor_project_details.scan_badge') }}: {{ ucfirst(str_replace('_', ' ', $project->scanner_status ?? 'not scanned')) }}</span>
                <span class="badge badge-{{ $riskClass }}">{{ __('backend.supervisor_project_details.risk_badge') }}: {{ $riskLevel ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- QUICK STATS --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="mini-card">
                <div class="mini-label">{{ __('backend.supervisor_project_details.scan_score') }}</div>
                <div class="mini-value">{{ $project->scan_score !== null ? number_format($project->scan_score, 2) : '-' }}</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="mini-card">
                <div class="mini-label">{{ __('backend.supervisor_project_details.scanner_project_id') }}</div>
                <div class="mini-value">{{ $project->scanner_project_id ?? '-' }}</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="mini-card">
                <div class="mini-label">{{ __('backend.supervisor_project_details.scanned_at') }}</div>
                <div class="mini-value" style="font-size:16px;">
                    {{ $project->scanned_at ? \Carbon\Carbon::parse($project->scanned_at)->format('d/m/Y H:i') : '-' }}
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="mini-card">
                <div class="mini-label">{{ __('backend.supervisor_project_details.investors_count') }}</div>
                <div class="mini-value">{{ $project->investors->count() }}</div>
            </div>
        </div>
    </div>

    @if($canSystemVerification)
    {{-- SYSTEM VERIFICATION --}}
    <div class="card section-card">
        <div class="card-header">
            <h4>{{ __('backend.supervisor_project_details.system_verification') }}</h4>
        </div>
        <div class="card-body">

            @if($latestSystemRequest && $latestSystemRequest->latestResponse)
                <div class="system-sync-alert">
                    <strong>{{ __('backend.supervisor_project_details.latest_system_response_detected') }}</strong>
                    {{ __('backend.supervisor_project_details.system_response_prefill_notice') }}
                </div>
            @endif

            <form method="POST" action="{{ route('supervisor.projects.system-verification.update', $project->project_id) }}">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="verification-box">
                            <label>{{ __('backend.supervisor_project_details.frontend_url') }}</label>
                            <input type="url" class="form-control" name="frontend_url" value="{{ $finalFrontendUrl }}" placeholder="https://your-app.com">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="verification-box">
                            <label>{{ __('backend.supervisor_project_details.backend_url') }}</label>
                            <input type="url" class="form-control" name="backend_url" value="{{ $finalBackendUrl }}" placeholder="https://api.your-app.com">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="verification-box">
                            <label>{{ __('backend.supervisor_project_details.api_health_url') }}</label>
                            <input type="url" class="form-control" name="api_health_url" value="{{ $finalApiHealthUrl }}" placeholder="https://api.your-app.com/health">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="verification-box">
                            <label>{{ __('backend.supervisor_project_details.admin_panel_url') }}</label>
                            <input type="url" class="form-control" name="admin_panel_url" value="{{ $finalAdminPanelUrl }}" placeholder="https://your-app.com/admin">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="verification-box">
                            <label>{{ __('backend.supervisor_project_details.demo_account') }}</label>
                            <input type="text" class="form-control" name="demo_account" value="{{ $finalDemoAccount }}" placeholder="test@example.com">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="verification-box">
                            <label>{{ __('backend.supervisor_project_details.demo_password') }}</label>
                            <input type="text" class="form-control" name="demo_password" value="{{ $finalDemoPassword }}" placeholder="Password">
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="verification-box">
                            <label>{{ __('backend.supervisor_project_details.deployment_notes') }}</label>
                            <textarea name="deployment_notes" rows="4" class="form-control" placeholder="{{ __('backend.supervisor_project_details.deployment_notes_placeholder') }}">{{ $finalDeploymentNotes }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">{{ __('backend.supervisor_project_details.save_verification') }}</button>
                    </div>
                </div>
            </form>

            <div class="verification-actions">
                @if($finalFrontendUrl)
                    <a href="{{ $finalFrontendUrl }}" target="_blank" class="verification-btn">{{ __('backend.supervisor_project_details.open_frontend') }}</a>
                @endif
                @if($finalBackendUrl)
                    <a href="{{ $finalBackendUrl }}" target="_blank" class="verification-btn">{{ __('backend.supervisor_project_details.open_backend') }}</a>
                @endif
                @if($finalApiHealthUrl)
                    <a href="{{ $finalApiHealthUrl }}" target="_blank" class="verification-btn">{{ __('backend.supervisor_project_details.check_api') }}</a>
                @endif
                @if($finalAdminPanelUrl)
                    <a href="{{ $finalAdminPanelUrl }}" target="_blank" class="verification-btn">{{ __('backend.supervisor_project_details.open_admin_panel') }}</a>
                @endif
            </div>

            @if($latestSystemRequest && $latestSystemRequest->latestResponse)
                <div class="system-grid">
                    <div class="system-info-card">
                        <div class="system-info-label">{{ __('backend.supervisor_project_details.frontend_url') }}</div>
                        <div class="system-info-value">{{ $parsedSystemResponse['frontend_url'] ?: '—' }}</div>
                    </div>
                    <div class="system-info-card">
                        <div class="system-info-label">{{ __('backend.supervisor_project_details.backend_url') }}</div>
                        <div class="system-info-value">{{ $parsedSystemResponse['backend_url'] ?: '—' }}</div>
                    </div>
                    <div class="system-info-card">
                        <div class="system-info-label">{{ __('backend.supervisor_project_details.api_health_url') }}</div>
                        <div class="system-info-value">{{ $parsedSystemResponse['api_health_url'] ?: '—' }}</div>
                    </div>
                    <div class="system-info-card">
                        <div class="system-info-label">{{ __('backend.supervisor_project_details.admin_panel_url') }}</div>
                        <div class="system-info-value">{{ $parsedSystemResponse['admin_panel_url'] ?: '—' }}</div>
                    </div>
                    <div class="system-info-card">
                        <div class="system-info-label">{{ __('backend.supervisor_project_details.demo_account') }}</div>
                        <div class="system-info-value">{{ $parsedSystemResponse['demo_account'] ?: '—' }}</div>
                    </div>
                    <div class="system-info-card">
                        <div class="system-info-label">{{ __('backend.supervisor_project_details.demo_password') }}</div>
                        <div class="system-info-value">{{ $parsedSystemResponse['demo_password'] ?: '—' }}</div>
                    </div>
                    <div class="system-info-card" style="grid-column: 1 / -1;">
                        <div class="system-info-label">{{ __('backend.supervisor_project_details.deployment_notes') }}</div>
                        <div class="system-info-value">{{ $parsedSystemResponse['deployment_notes'] ?: '—' }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif

    @if($canEvaluateProjects)
    {{-- SUPERVISOR EVALUATION --}}
    <div class="card section-card">
        <div class="card-header">
            <h4>{{ __('backend.supervisor_project_details.supervisor_evaluation') }}</h4>
        </div>
        <div class="card-body">

            <form method="POST" action="{{ route('supervisor.projects.evaluation.store', $project->project_id) }}" class="mb-4">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="request-box">
                            <label>{{ __('backend.supervisor_project_details.score') }}</label>
                            <input
                                type="number"
                                name="score"
                                class="form-control"
                                min="0"
                                max="100"
                                value="{{ old('score', $currentSupervisorReview->score ?? '') }}"
                                placeholder="{{ __('backend.supervisor_project_details.score_placeholder') }}">
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="request-box">
                            <label>{{ __('backend.supervisor_project_details.decision') }}</label>
                            <select name="decision" class="form-control" required>
                                <option value="">{{ __('backend.supervisor_project_details.select_decision') }}</option>
                                <option value="approved" {{ old('decision', $currentSupervisorReview->decision ?? '') === 'approved' ? 'selected' : '' }}>
                                    {{ __('backend.supervisor_project_details.approved') }}
                                </option>
                                <option value="revision_requested" {{ old('decision', $currentSupervisorReview->decision ?? '') === 'revision_requested' ? 'selected' : '' }}>
                                    {{ __('backend.supervisor_project_details.revision_requested') }}
                                </option>
                                <option value="rejected" {{ old('decision', $currentSupervisorReview->decision ?? '') === 'rejected' ? 'selected' : '' }}>
                                    {{ __('backend.supervisor_project_details.rejected') }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="request-box">
                            <label>{{ __('backend.supervisor_project_details.reviewed_at') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $currentSupervisorReview && $currentSupervisorReview->reviewed_at ? \Carbon\Carbon::parse($currentSupervisorReview->reviewed_at)->format('d/m/Y h:i A') : __('backend.supervisor_project_details.not_reviewed_yet') }}"
                                readonly>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="request-box">
                            <label>{{ __('backend.supervisor_project_details.supervisor_notes') }}</label>
                            <textarea
                                name="notes"
                                rows="5"
                                class="form-control"
                                placeholder="{{ __('backend.supervisor_project_details.supervisor_notes_placeholder') }}"
                                required>{{ old('notes', $currentSupervisorReview->notes ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">
                            {{ $currentSupervisorReview ? __('backend.supervisor_project_details.update_evaluation') : __('backend.supervisor_project_details.submit_evaluation') }}
                        </button>
                    </div>
                </div>
            </form>

            <hr>

            <h5 class="mb-3">{{ __('backend.supervisor_project_details.all_supervisor_evaluations') }}</h5>

            @forelse($project->reviews as $review)
                @php
                    $decisionClass = match($review->decision) {
                        'approved' => 'badge-review-approved',
                        'revision_requested' => 'badge-review-revision_requested',
                        'rejected' => 'badge-review-rejected',
                        default => 'badge-review-pending',
                    };
                @endphp

                <div class="request-item">
                    <div class="request-title">
                        {{ $review->supervisor?->name ?? __('backend.supervisor_project_details.supervisor') }}
                    </div>

                    <div class="request-meta">
                        {{ __('backend.supervisor_project_details.decision_label') }}:
                        <span class="badge-soft {{ $decisionClass }}">
                            {{ ucfirst(str_replace('_', ' ', $review->decision)) }}
                        </span>

                        @if($review->reviewed_at)
                            • {{ __('backend.supervisor_project_details.reviewed_label') }}: {{ \Carbon\Carbon::parse($review->reviewed_at)->format('d/m/Y h:i A') }}
                        @endif

                        @if(!is_null($review->score))
                            • {{ __('backend.supervisor_project_details.score_label') }}: {{ $review->score }}/100
                        @endif
                    </div>

                    <div class="text-muted" style="white-space: pre-line;">
                        {{ $review->notes }}
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0">{{ __('backend.supervisor_project_details.no_supervisor_evaluations') }}</p>
            @endforelse

        </div>
    </div>
    @endif

    @if($canViewRequests || $canManageRequests)
    {{-- REQUESTS TO STUDENT --}}
    <div class="card section-card">
        <div class="card-header">
            <h4>{{ __('backend.supervisor_project_details.requests_to_student') }}</h4>
        </div>
        <div class="card-body">

            @if($canManageRequests)
            <div class="template-helper">
                {!! __('backend.supervisor_project_details.request_template_helper') !!}
            </div>

            <div class="request-template-bar">
                <button type="button" class="template-btn system" onclick="fillSystemRequestTemplate()">
                    <i class="fa fa-cogs me-1"></i> {{ __('backend.supervisor_project_details.use_system_request_template') }}
                </button>

                <button type="button" class="template-btn" onclick="fillMinimalSystemRequestTemplate()">
                    <i class="fa fa-shield me-1"></i> {{ __('backend.supervisor_project_details.use_minimal_system_request') }}
                </button>

                <button type="button" class="template-btn" onclick="clearRequestTemplate()">
                    <i class="fa fa-eraser me-1"></i> {{ __('backend.supervisor_project_details.clear_form') }}
                </button>
            </div>

            <form method="POST" action="{{ route('supervisor.projects.requests.store', $project->project_id) }}" class="mb-4">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="request-box">
                            <label>{{ __('backend.supervisor_project_details.request_title') }}</label>
                            <input type="text" id="request_title" name="title" class="form-control" value="{{ old('title') }}" placeholder="{{ __('backend.supervisor_project_details.request_title_placeholder') }}" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="request-box">
                            <label>{{ __('backend.supervisor_project_details.request_type') }}</label>
                            <select id="request_type" name="request_type" class="form-control" required>
                                <option value="">{{ __('backend.supervisor_project_details.select_type') }}</option>
                                <option value="system_verification">{{ __('backend.supervisor_project_details.types.system_verification') }}</option>
                                <option value="github_link">{{ __('backend.supervisor_project_details.types.github_link') }}</option>
                                <option value="deployment_link">{{ __('backend.supervisor_project_details.types.deployment_link') }}</option>
                                <option value="images">{{ __('backend.supervisor_project_details.types.images') }}</option>
                                <option value="video_demo">{{ __('backend.supervisor_project_details.types.video_demo') }}</option>
                                <option value="documentation">{{ __('backend.supervisor_project_details.types.documentation') }}</option>
                                <option value="pdf_file">{{ __('backend.supervisor_project_details.types.pdf_file') }}</option>
                                <option value="source_code">{{ __('backend.supervisor_project_details.types.source_code') }}</option>
                                <option value="presentation">{{ __('backend.supervisor_project_details.types.presentation') }}</option>
                                <option value="clarification">{{ __('backend.supervisor_project_details.types.clarification') }}</option>
                                <option value="other">{{ __('backend.supervisor_project_details.types.other') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="request-box">
                            <label>{{ __('backend.supervisor_project_details.description') }}</label>
                            <textarea id="request_description" name="description" rows="8" class="form-control" placeholder="{{ __('backend.supervisor_project_details.request_description_placeholder') }}">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="request-box">
                            <label>{{ __('backend.supervisor_project_details.due_date') }}</label>
                            <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-dark">{{ __('backend.supervisor_project_details.send_request') }}</button>
                    </div>
                </div>
            </form>

            <hr>
            @endif

            <h5 class="mb-3">{{ __('backend.supervisor_project_details.student_requests') }}</h5>

            @forelse($projectRequests as $requestItem)
                @php
                    $isSystemRequest = strtolower($requestItem->request_type ?? '') === 'system_verification';
                    $collapseId = 'response-collapse-' . $requestItem->id;
                    $buttonId = 'response-button-' . $requestItem->id;
                @endphp

                <div class="request-item">
                    <div class="request-title">{{ $requestItem->title }}</div>

                    <div class="request-meta">
                        {{ ucfirst(str_replace('_', ' ', $requestItem->request_type)) }}
                        @if($requestItem->due_date)
                            • {{ __('backend.supervisor_project_details.due_label') }}: {{ \Carbon\Carbon::parse($requestItem->due_date)->format('d/m/Y') }}
                        @endif
                    </div>

                    <div class="mb-2 text-muted">
                        {!! nl2br(e($requestItem->description ?: __('backend.supervisor_project_details.no_description_provided'))) !!}
                    </div>

                    @if($canManageRequests)
                    <div class="request-actions">
                        <form method="POST" action="{{ route('supervisor.requests.status', $requestItem->id) }}">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <button class="btn btn-success btn-sm">{{ __('backend.supervisor_project_details.mark_completed') }}</button>
                        </form>

                        <form method="POST" action="{{ route('supervisor.requests.status', $requestItem->id) }}">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button class="btn btn-danger btn-sm">{{ __('backend.supervisor_project_details.cancel') }}</button>
                        </form>

                        <form method="POST" action="{{ route('supervisor.requests.status', $requestItem->id) }}">
                            @csrf
                            <input type="hidden" name="status" value="pending">
                            <button class="btn btn-secondary btn-sm">{{ __('backend.supervisor_project_details.reset') }}</button>
                        </form>

                        <span class="badge bg-light text-dark border align-self-center">
                            {{ ucfirst($requestItem->status ?? 'pending') }}
                        </span>
                    </div>
                    @else
                    <div class="request-actions">
                        <span class="badge bg-light text-dark border align-self-center">
                            {{ ucfirst($requestItem->status ?? 'pending') }}
                        </span>
                    </div>
                    @endif

                    @if($requestItem->latestResponse)
                        <button type="button"
                                id="{{ $buttonId }}"
                                class="response-toggle-btn"
                                onclick="toggleResponse('{{ $collapseId }}', '{{ $buttonId }}')">
                            <span>
                                {{ $isSystemRequest ? __('backend.supervisor_project_details.view_student_system_response') : __('backend.supervisor_project_details.view_student_response') }}
                            </span>
                            <i class="fa fa-chevron-down response-toggle-icon"></i>
                        </button>

                        <div id="{{ $collapseId }}" class="response-collapse">
                            @if($isSystemRequest)
                                @php
                                    $systemText = $requestItem->latestResponse->response_text ?? '';

                                    $reqParsed = [
                                        'frontend_url'     => null,
                                        'backend_url'      => null,
                                        'api_health_url'   => null,
                                        'admin_panel_url'  => null,
                                        'demo_account'     => null,
                                        'demo_password'    => null,
                                        'deployment_notes' => null,
                                    ];

                                    if (preg_match('/Frontend URL:\s*(.+)/i', $systemText, $m)) {
                                        $reqParsed['frontend_url'] = trim($m[1]);
                                    }
                                    if (preg_match('/Backend URL:\s*(.+)/i', $systemText, $m)) {
                                        $reqParsed['backend_url'] = trim($m[1]);
                                    }
                                    if (preg_match('/API Health \/ API Base URL:\s*(.+)/i', $systemText, $m)) {
                                        $reqParsed['api_health_url'] = trim($m[1]);
                                    } elseif (preg_match('/API URL:\s*(.+)/i', $systemText, $m)) {
                                        $reqParsed['api_health_url'] = trim($m[1]);
                                    } elseif (preg_match('/API Health URL:\s*(.+)/i', $systemText, $m)) {
                                        $reqParsed['api_health_url'] = trim($m[1]);
                                    }
                                    if (preg_match('/Admin Panel URL:\s*(.+)/i', $systemText, $m)) {
                                        $reqParsed['admin_panel_url'] = trim($m[1]);
                                    }
                                    if (preg_match('/Demo Account:\s*(.+)/i', $systemText, $m)) {
                                        $reqParsed['demo_account'] = trim($m[1]);
                                    }
                                    if (preg_match('/Demo Password:\s*(.+)/i', $systemText, $m)) {
                                        $reqParsed['demo_password'] = trim($m[1]);
                                    }
                                    if (preg_match('/Deployment Notes:\s*([\s\S]+)/i', $systemText, $m)) {
                                        $reqParsed['deployment_notes'] = trim($m[1]);
                                    }
                                @endphp

                                <div class="student-response-box">
                                    <div class="student-response-title">{{ __('backend.supervisor_project_details.latest_student_system_verification_response') }}</div>

                                    <div class="student-response-meta">
                                        {{ __('backend.supervisor_project_details.student') }}: {{ $requestItem->student?->name ?? __('backend.supervisor_project_details.student_fallback') }}
                                        @if($requestItem->latestResponse->submitted_at)
                                            • {{ __('backend.supervisor_project_details.submitted_label') }}: {{ \Carbon\Carbon::parse($requestItem->latestResponse->submitted_at)->format('d/m/Y h:i A') }}
                                        @endif
                                    </div>

                                    <div class="system-grid">
                                        <div class="system-info-card">
                                            <div class="system-info-label">{{ __('backend.supervisor_project_details.frontend_url') }}</div>
                                            <div class="system-info-value">{{ $reqParsed['frontend_url'] ?: '—' }}</div>
                                        </div>

                                        <div class="system-info-card">
                                            <div class="system-info-label">{{ __('backend.supervisor_project_details.backend_url') }}</div>
                                            <div class="system-info-value">{{ $reqParsed['backend_url'] ?: '—' }}</div>
                                        </div>

                                        <div class="system-info-card">
                                            <div class="system-info-label">{{ __('backend.supervisor_project_details.api_health_url') }}</div>
                                            <div class="system-info-value">{{ $reqParsed['api_health_url'] ?: '—' }}</div>
                                        </div>

                                        <div class="system-info-card">
                                            <div class="system-info-label">{{ __('backend.supervisor_project_details.admin_panel_url') }}</div>
                                            <div class="system-info-value">{{ $reqParsed['admin_panel_url'] ?: '—' }}</div>
                                        </div>

                                        <div class="system-info-card">
                                            <div class="system-info-label">{{ __('backend.supervisor_project_details.demo_account') }}</div>
                                            <div class="system-info-value">{{ $reqParsed['demo_account'] ?: '—' }}</div>
                                        </div>

                                        <div class="system-info-card">
                                            <div class="system-info-label">{{ __('backend.supervisor_project_details.demo_password') }}</div>
                                            <div class="system-info-value">{{ $reqParsed['demo_password'] ?: '—' }}</div>
                                        </div>

                                        <div class="system-info-card" style="grid-column: 1 / -1;">
                                            <div class="system-info-label">{{ __('backend.supervisor_project_details.deployment_notes') }}</div>
                                            <div class="system-info-value">{{ $reqParsed['deployment_notes'] ?: '—' }}</div>
                                        </div>
                                    </div>

                                    @if($requestItem->latestResponse->attachment_path)
                                        <div class="student-response-links mt-3">
                                            <a href="{{ route('supervisor.file.view', $requestItem->latestResponse->id) }}" target="_blank" class="response-link-btn">
                                                {{ __('backend.supervisor_project_details.download_attachment') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="student-response-box">
                                    <div class="student-response-title">{{ __('backend.supervisor_project_details.latest_student_response') }}</div>

                                    <div class="student-response-meta">
                                        {{ __('backend.supervisor_project_details.student') }}: {{ $requestItem->student?->name ?? __('backend.supervisor_project_details.student_fallback') }}
                                        @if($requestItem->latestResponse->submitted_at)
                                            • {{ __('backend.supervisor_project_details.submitted_label') }}: {{ \Carbon\Carbon::parse($requestItem->latestResponse->submitted_at)->format('d/m/Y h:i A') }}
                                        @endif
                                    </div>

                                    @if($requestItem->latestResponse->response_text)
                                        <div class="student-response-text">
                                            {{ $requestItem->latestResponse->response_text }}
                                        </div>
                                    @endif

                                    @if($requestItem->latestResponse->response_link || $requestItem->latestResponse->attachment_path)
                                        <div class="student-response-links">
                                            @if($requestItem->latestResponse->response_link)
                                                <a href="{{ $requestItem->latestResponse->response_link }}" target="_blank" class="response-link-btn">
                                                    {{ __('backend.supervisor_project_details.open_submitted_link') }}
                                                </a>
                                            @endif

                                            @if($requestItem->latestResponse->attachment_path)
                                                <a href="{{ route('supervisor.file.view', $requestItem->latestResponse->id) }}" target="_blank" class="response-link-btn">
                                                    {{ __('backend.supervisor_project_details.download_attachment') }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="student-response-box">
                            <div class="student-response-title">{{ __('backend.supervisor_project_details.student_response') }}</div>
                            <div class="text-muted mb-0">{{ __('backend.supervisor_project_details.no_student_response_yet') }}</div>
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-muted mb-0">{{ __('backend.supervisor_project_details.no_requests_sent_yet') }}</p>
            @endforelse
        </div>
    </div>
    @endif

    @if($canViewMeetings || $canManageMeetings)
    {{-- MEETINGS --}}
    <div class="card section-card">
        <div class="card-header">
            <h4>{{ __('backend.supervisor_project_details.meetings_demo') }}</h4>
        </div>
        <div class="card-body">

            @if($canManageMeetings)
            <form method="POST" action="{{ route('supervisor.projects.meetings.store') }}" class="mb-4">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->project_id }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('backend.supervisor_project_details.meeting_title') }}</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="{{ __('backend.supervisor_project_details.meeting_title_placeholder') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('backend.supervisor_project_details.meeting_type') }}</label>
                        <select name="meeting_type" class="form-control" required>
                            <option value="online">{{ __('backend.supervisor_project_details.meeting_types.online') }}</option>
                            <option value="offline">{{ __('backend.supervisor_project_details.meeting_types.offline') }}</option>
                            <option value="demo">{{ __('backend.supervisor_project_details.meeting_types.demo') }}</option>
                            <option value="viva">{{ __('backend.supervisor_project_details.meeting_types.viva') }}</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('backend.supervisor_project_details.meeting_date') }}</label>
                        <input type="date" name="meeting_date" class="form-control" value="{{ old('meeting_date') }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('backend.supervisor_project_details.meeting_time') }}</label>
                        <input type="time" name="meeting_time" class="form-control" value="{{ old('meeting_time') }}" required>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">{{ __('backend.supervisor_project_details.meeting_link') }}</label>
                        <input type="url" name="meeting_link" class="form-control" value="{{ old('meeting_link') }}" placeholder="https://meet.google.com/...">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">{{ __('backend.supervisor_project_details.notes') }}</label>
                        <textarea name="notes" rows="4" class="form-control" placeholder="{{ __('backend.supervisor_project_details.meeting_notes_placeholder') }}">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-success">{{ __('backend.supervisor_project_details.schedule_meeting') }}</button>
                    </div>
                </div>
            </form>

            <hr>
            @endif

            <h5 class="mb-3">{{ __('backend.supervisor_project_details.scheduled_meetings') }}</h5>

            @forelse($meetings->sortByDesc('meeting_date') as $meeting)
                <div class="meeting-item">
                    <div class="meeting-title">{{ $meeting->title }}</div>
                    <div class="meeting-meta">
                        {{ ucfirst($meeting->meeting_type) }} • {{ $meeting->meeting_date }} • {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}
                    </div>

                    @if($meeting->meeting_link)
                        <div class="mb-2">
                            <a href="{{ $meeting->meeting_link }}" target="_blank" class="verification-btn">{{ __('backend.supervisor_project_details.open_meeting_link') }}</a>
                        </div>
                    @endif

                    @if($meeting->notes)
                        <div class="text-muted mb-3">{{ $meeting->notes }}</div>
                    @endif

                    @if($canManageMeetings)
                    <div class="meeting-actions">
                        <form method="POST" action="{{ route('supervisor.projects.meetings.status', ['project' => $project->project_id, 'meeting' => $meeting->id]) }}">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <button class="btn btn-success btn-sm">{{ __('backend.supervisor_project_details.complete') }}</button>
                        </form>

                        <form method="POST" action="{{ route('supervisor.projects.meetings.status', ['project' => $project->project_id, 'meeting' => $meeting->id]) }}">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button class="btn btn-danger btn-sm">{{ __('backend.supervisor_project_details.cancel') }}</button>
                        </form>

                        <form method="POST" action="{{ route('supervisor.projects.meetings.status', ['project' => $project->project_id, 'meeting' => $meeting->id]) }}">
                            @csrf
                            <input type="hidden" name="status" value="scheduled">
                            <button class="btn btn-secondary btn-sm">{{ __('backend.supervisor_project_details.reset') }}</button>
                        </form>

                        <span class="badge bg-light text-dark border align-self-center">
                            {{ ucfirst($meeting->status) }}
                        </span>
                    </div>
                    @else
                    <div class="meeting-actions">
                        <span class="badge bg-light text-dark border align-self-center">
                            {{ ucfirst($meeting->status) }}
                        </span>
                    </div>
                    @endif
                </div>
            @empty
                <p class="text-muted mb-0">{{ __('backend.supervisor_project_details.no_meetings_scheduled') }}</p>
            @endforelse
        </div>
    </div>
    @endif

    {{-- PROJECT OVERVIEW --}}
    <div class="card section-card">
        <div class="card-header">
            <h4>{{ __('backend.supervisor_project_details.project_overview') }}</h4>
        </div>
        <div class="card-body">
            <div class="row detail-grid">
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.status') }}</div>
                    <div class="detail-value">{{ $project->status ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.progress') }}</div>
                    <div class="detail-value">{{ $project->progress ?? 0 }}%</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.category') }}</div>
                    <div class="detail-value">{{ $project->category ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.budget') }}</div>
                    <div class="detail-value">{{ $project->budget ?? '-' }}</div>
                </div>

                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.priority') }}</div>
                    <div class="detail-value">{{ $project->priority ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.start_date') }}</div>
                    <div class="detail-value">{{ optional($project->start_date)->format('d/m/Y') ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.end_date') }}</div>
                    <div class="detail-value">{{ optional($project->end_date)->format('d/m/Y') ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.created_at') }}</div>
                    <div class="detail-value">{{ optional($project->created_at)->format('d/m/Y H:i') ?? '-' }}</div>
                </div>

                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.scanner_status') }}</div>
                    <div class="detail-value">{{ $project->scanner_status ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.scanner_project_id') }}</div>
                    <div class="detail-value">{{ $project->scanner_project_id ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.scan_score') }}</div>
                    <div class="detail-value">{{ $project->scan_score !== null ? number_format($project->scan_score, 2) : '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.risk_level') }}</div>
                    <div class="detail-value">{{ $riskLevel ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- PEOPLE --}}
    <div class="card section-card">
        <div class="card-header">
            <h4>{{ __('backend.supervisor_project_details.people_assignment') }}</h4>
        </div>
        <div class="card-body">
            <div class="row detail-grid">
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.student') }}</div>
                    <div class="detail-value">{{ $project->student?->name ?? '-' }}</div>
                    <div class="text-muted small">{{ $project->student?->email ?? '' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.supervisor') }}</div>
                    <div class="detail-value">{{ $project->supervisor?->name ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.manager') }}</div>
                    <div class="detail-value">{{ $project->manager?->name ?? '-' }}</div>
                </div>
                <div class="col-md-3 item">
                    <div class="detail-label">{{ __('backend.supervisor_project_details.total_investors') }}</div>
                    <div class="detail-value">{{ $project->investors->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCAN SUMMARY --}}
    <div class="card section-card">
        <div class="card-header">
            <h4>{{ __('backend.supervisor_project_details.scan_intelligence_summary') }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 mb-3">
                    <div class="scan-score-box">
                        <div class="scan-score-number">{{ $project->scan_score !== null ? number_format($project->scan_score, 0) : '-' }}</div>
                        <div class="scan-score-label">{{ __('backend.supervisor_project_details.overall_score') }}</div>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="summary-box">
                        <h6>{{ __('backend.supervisor_project_details.scan_metadata') }}</h6>
                        <ul class="summary-list">
                            <li><strong>{{ __('backend.supervisor_project_details.event') }}:</strong> {{ data_get($scanReport, 'event', '-') }}</li>
                            <li><strong>{{ __('backend.supervisor_project_details.version') }}:</strong> {{ data_get($scanReport, 'version', '-') }}</li>
                            <li><strong>{{ __('backend.supervisor_project_details.grade') }}:</strong> {{ data_get($scanInfo, 'grade', $project->grade ?? '-') }}</li>
                            <li><strong>{{ __('backend.supervisor_project_details.status') }}:</strong> {{ data_get($scanInfo, 'status', $project->scanner_status ?? '-') }}</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="summary-box">
                        <h6>{{ __('backend.supervisor_project_details.issue_summary') }}</h6>
                        <ul class="summary-list">
                            <li><strong>{{ __('backend.supervisor_project_details.total_files') }}:</strong> {{ data_get($scanSummary, 'total_files', '-') }}</li>
                            <li><strong>{{ __('backend.supervisor_project_details.total_issues') }}:</strong> {{ data_get($scanSummary, 'issues_total', '-') }}</li>
                            <li><strong>{{ __('backend.supervisor_project_details.critical') }}:</strong> {{ data_get($scanSummary, 'critical', '-') }}</li>
                            <li><strong>{{ __('backend.supervisor_project_details.high') }}:</strong> {{ data_get($scanSummary, 'high', '-') }}</li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-3 mb-3">
                    <div class="summary-box">
                        <h6>{{ __('backend.supervisor_project_details.more_details') }}</h6>
                        <ul class="summary-list">
                            <li><strong>{{ __('backend.supervisor_project_details.medium') }}:</strong> {{ data_get($scanSummary, 'medium', '-') }}</li>
                            <li><strong>{{ __('backend.supervisor_project_details.low') }}:</strong> {{ data_get($scanSummary, 'low', '-') }}</li>
                            <li><strong>{{ __('backend.supervisor_project_details.language') }}:</strong> {{ data_get($scanProject, 'language', '-') }}</li>
                            <li><strong>{{ __('backend.supervisor_project_details.scanned_at') }}:</strong> {{ $project->scanned_at ? \Carbon\Carbon::parse($project->scanned_at)->format('d/m/Y H:i') : '-' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5 class="mb-3">{{ __('backend.supervisor_project_details.highlights') }}</h5>
                    @if(!empty($highlights))
                        <ul class="highlight-list mb-0">
                            @foreach($highlights as $highlight)
                                <li>{{ $highlight }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">{{ __('backend.supervisor_project_details.no_highlights') }}</p>
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <h5 class="mb-3">{{ __('backend.supervisor_project_details.recommendations') }}</h5>
                    @if(!empty($recommendations))
                        <ul class="recommend-list mb-0">
                            @foreach($recommendations as $recommendation)
                                <li>{{ $recommendation }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">{{ __('backend.supervisor_project_details.no_recommendations') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MEDIA --}}
    <div class="card section-card">
        <div class="card-header">
            <h4>{{ __('backend.supervisor_project_details.project_media') }}</h4>
        </div>
        <div class="card-body">
            @php
                $images = method_exists($project, 'getMedia') ? $project->getMedia('images') : collect();
                $videoUrl = method_exists($project, 'getFirstMediaUrl') ? $project->getFirstMediaUrl('videos') : null;
            @endphp

            <h5 class="mb-3">{{ __('backend.supervisor_project_details.images') }} ({{ $images->count() }})</h5>

            @if($images->count())
                <div class="row">
                    @foreach($images as $img)
                        <div class="col-md-3 mb-3">
                            <a href="{{ $img->getUrl() }}" target="_blank" class="d-block">
                                <img src="{{ $img->getUrl() }}" class="img-fluid rounded border" alt="{{ __('backend.supervisor_project_details.project_image_alt') }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">{{ __('backend.supervisor_project_details.no_images_uploaded') }}</p>
            @endif

            <hr>

            <h5 class="mb-3">{{ __('backend.supervisor_project_details.video') }}</h5>

            @if($videoUrl)
                <video class="w-100 rounded border" controls style="max-height:420px;">
                    <source src="{{ $videoUrl }}" type="video/mp4">
                    {{ __('backend.supervisor_project_details.video_not_supported') }}
                </video>
            @else
                <p class="text-muted">{{ __('backend.supervisor_project_details.no_video_uploaded') }}</p>
            @endif
        </div>
    </div>

</div>

<script>
    function fillSystemRequestTemplate() {
        document.getElementById('request_title').value = @json(__('backend.supervisor_project_details.templates.system_request_title'));
        document.getElementById('request_type').value = 'system_verification';
        document.getElementById('request_description').value = @json(__('backend.supervisor_project_details.templates.system_request_description'));
    }

    function fillMinimalSystemRequestTemplate() {
        document.getElementById('request_title').value = @json(__('backend.supervisor_project_details.templates.minimal_system_request_title'));
        document.getElementById('request_type').value = 'system_verification';
        document.getElementById('request_description').value = @json(__('backend.supervisor_project_details.templates.minimal_system_request_description'));
    }

    function clearRequestTemplate() {
        document.getElementById('request_title').value = '';
        document.getElementById('request_type').value = '';
        document.getElementById('request_description').value = '';
    }

    function toggleResponse(collapseId, buttonId) {
        const collapse = document.getElementById(collapseId);
        const button = document.getElementById(buttonId);

        if (collapse.classList.contains('show')) {
            collapse.classList.remove('show');
            button.classList.remove('active');
        } else {
            collapse.classList.add('show');
            button.classList.add('active');
        }
    }
</script>
@endsection