@extends('layouts.app')

@section('title', __('backend.projects_edit.page_title'))

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
    }

    body { background: var(--page-bg); }

    .edit-project-page { padding: 10px 0 24px; }

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

    .search-btn {
        min-height: 46px;
        border-radius: 14px;
        font-weight: 700;
        padding: 10px 18px;
    }

    .main-panel {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        margin-bottom: 24px;
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

    .form-label-custom {
        font-size: 0.82rem;
        color: var(--text-soft);
        font-weight: 700;
        margin-bottom: 8px;
        display: block;
    }

    .form-control.custom-input,
    .form-select.custom-input {
        min-height: 46px;
        border-radius: 14px;
        border: 1px solid #dfe5ef;
        box-shadow: none;
        padding: 12px 14px;
    }

    .form-control.custom-input:focus,
    .form-select.custom-input:focus {
        border-color: rgba(78, 115, 223, 0.5);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.12);
    }

    textarea.custom-input {
        min-height: 120px;
        resize: vertical;
    }

    .notice-box {
        border-radius: 16px;
        padding: 16px 18px;
        margin-bottom: 18px;
        border: 1px solid transparent;
    }

    .notice-info {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #1e40af;
    }

    .notice-success {
        background: #ecfdf5;
        border-color: #bbf7d0;
        color: #166534;
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 16px;
        height: 100%;
    }

    .info-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .info-value {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        word-break: break-word;
    }

    .badge-soft {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .badge-status-pending {
        background: #fff7ed;
        color: #c2410c;
    }

    .badge-status-approved {
        background: #ecfdf5;
        color: #15803d;
    }

    .badge-status-rejected {
        background: #fef2f2;
        color: #dc2626;
    }

    .badge-status-default {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .action-bar {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .student-row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 12px;
        align-items: end;
    }

    @media (max-width: 768px) {
        .student-row { grid-template-columns: 1fr; }
    }

    @media (max-width: 991px) {
        .page-header-card { padding: 22px 20px; }
        .panel-head, .table-wrap { padding-left: 18px; padding-right: 18px; }
    }

    @media (max-width: 576px) {
        .page-title { font-size: 1.3rem; }
    }
</style>

<div class="container-fluid edit-project-page">

    @if(session('error'))
        <div class="alert alert-danger custom-alert mb-4">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger custom-alert mb-4">
            <strong>{{ __('backend.projects_edit.please_fix_errors') }}</strong>
            <ul class="mb-0 mt-2 pl-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.projects_edit.heading') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.projects_edit.subtitle') }}
                </p>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.projects.show', $project) }}" class="reset-btn">
                    <i class="fa fa-eye mr-1"></i> {{ __('backend.projects_edit.view_project') }}
                </a>
                <a href="{{ route('admin.projects.index') }}" class="reset-btn">
                    <i class="fa fa-arrow-left mr-1"></i> {{ __('backend.projects_edit.back') }}
                </a>
            </div>
        </div>
    </div>

    @if(in_array($project->status, ['pending', 'active', 'draft']))
        <div class="notice-box notice-info">
            <strong>{{ __('backend.projects_edit.scanner_action_available') }}</strong>
            {{ __('backend.projects_edit.scanner_action_available_text') }}
        </div>
    @endif

    @if(in_array($project->status, ['scan_requested', 'awaiting_manual_review']))
        <div class="notice-box notice-info">
            <strong>{{ __('backend.projects_edit.project_under_review') }}</strong>
            {{ __('backend.projects_edit.project_under_review_text') }}
        </div>
    @endif

    @if(in_array($project->status, ['approved', 'published', 'completed']))
        <div class="notice-box notice-success">
            <strong>{{ __('backend.projects_edit.approved_workflow') }}</strong>
            {{ __('backend.projects_edit.approved_workflow_text') }}
        </div>
    @endif

    <div class="main-panel form-animate">
        <div class="panel-head">
            <h2 class="panel-title">{{ __('backend.projects_edit.project_actions') }}</h2>
            <div class="panel-subtitle">{{ __('backend.projects_edit.project_actions_subtitle') }}</div>
        </div>

        <div class="table-wrap">
            <div class="action-bar">
                <a href="{{ route('admin.projects.scannerReview', $project) }}" class="btn btn-outline-info search-btn">
                    <i class="fa fa-search mr-1"></i> {{ __('backend.projects_edit.go_to_scan_page') }}
                </a>

                <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-outline-primary search-btn">
                    <i class="fa fa-folder-open mr-1"></i> {{ __('backend.projects_edit.open_details') }}
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.projects.update', $project) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="main-panel form-animate">
            <div class="panel-head">
                <h2 class="panel-title">{{ __('backend.projects_edit.project_overview') }}</h2>
                <div class="panel-subtitle">{{ __('backend.projects_edit.project_overview_subtitle') }}</div>
            </div>

            <div class="table-wrap">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="mb-3">
                            <label class="form-label-custom" for="name">{{ __('backend.projects_edit.project_name') }}</label>
                            <input type="text" id="name" name="name" class="form-control custom-input" value="{{ old('name', $project->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom" for="category">{{ __('backend.projects_edit.category') }}</label>
                            <input type="text" id="category" name="category" class="form-control custom-input" value="{{ old('category', $project->category) }}" placeholder="{{ __('backend.projects_edit.category_placeholder') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom" for="budget">{{ __('backend.projects_edit.budget') }}</label>
                            <input type="number" step="0.01" id="budget" name="budget" class="form-control custom-input" value="{{ old('budget', $project->budget) }}" placeholder="0.00">
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom" for="priority">{{ __('backend.projects_edit.priority') }}</label>
                            <select id="priority" name="priority" class="form-select custom-input">
                                <option value="Low" {{ old('priority', $project->priority) === 'Low' ? 'selected' : '' }}>{{ __('backend.projects_edit.priority_low') }}</option>
                                <option value="Medium" {{ old('priority', $project->priority) === 'Medium' ? 'selected' : '' }}>{{ __('backend.projects_edit.priority_medium') }}</option>
                                <option value="High" {{ old('priority', $project->priority) === 'High' ? 'selected' : '' }}>{{ __('backend.projects_edit.priority_high') }}</option>
                            </select>
                        </div>

                        <div class="mb-0">
                            <label class="form-label-custom" for="description">{{ __('backend.projects_edit.description') }}</label>
                            <textarea id="description" name="description" class="form-control custom-input" rows="6" placeholder="{{ __('backend.projects_edit.description_placeholder') }}">{{ old('description', $project->description) }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        @php
                            $statusClass = match($project->status) {
                                'pending', 'scan_requested', 'awaiting_manual_review' => 'badge-status-pending',
                                'approved', 'published', 'completed' => 'badge-status-approved',
                                'rejected', 'scan_failed' => 'badge-status-rejected',
                                default => 'badge-status-default',
                            };
                        @endphp

                        <div class="mb-3">
                            <span class="badge-soft {{ $statusClass }}">
                                {{ __('backend.projects_edit.current_status') }} {{ ucfirst(str_replace('_', ' ', $project->status ?? 'unknown')) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-custom" for="status">{{ __('backend.projects_edit.project_status') }}</label>
                            <select id="status" name="status" class="form-select custom-input" required>
                                <option value="draft" {{ old('status', $project->status) === 'draft' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_draft') }}</option>
                                <option value="pending" {{ old('status', $project->status) === 'pending' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_pending') }}</option>
                                <option value="scan_requested" {{ old('status', $project->status) === 'scan_requested' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_scan_requested') }}</option>
                                <option value="awaiting_manual_review" {{ old('status', $project->status) === 'awaiting_manual_review' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_awaiting_manual_review') }}</option>
                                <option value="approved" {{ old('status', $project->status) === 'approved' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_approved') }}</option>
                                <option value="published" {{ old('status', $project->status) === 'published' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_published') }}</option>
                                <option value="active" {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_active') }}</option>
                                <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_completed') }}</option>
                                <option value="rejected" {{ old('status', $project->status) === 'rejected' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_rejected') }}</option>
                                <option value="scan_failed" {{ old('status', $project->status) === 'scan_failed' ? 'selected' : '' }}>{{ __('backend.projects_edit.status_scan_failed') }}</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">{{ __('backend.projects_edit.project_id') }}</div>
                                    <div class="info-value">{{ $project->project_id ?? $project->id }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">{{ __('backend.projects_edit.scanner_status') }}</div>
                                    <div class="info-value">{{ $project->scanner_status ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">{{ __('backend.projects_edit.scanner_project_id') }}</div>
                                    <div class="info-value">{{ $project->scanner_project_id ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">{{ __('backend.projects_edit.scan_score') }}</div>
                                    <div class="info-value">{{ $project->scan_score ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">{{ __('backend.projects_edit.created_at') }}</div>
                                    <div class="info-value">{{ optional($project->created_at)->format('Y-m-d h:i A') ?? '—' }}</div>
                                </div>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <div class="info-box">
                                    <div class="info-label">{{ __('backend.projects_edit.updated_at') }}</div>
                                    <div class="info-value">{{ optional($project->updated_at)->format('Y-m-d h:i A') ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-panel form-animate">
            <div class="panel-head">
                <h2 class="panel-title">{{ __('backend.projects_edit.assignments') }}</h2>
                <div class="panel-subtitle">{{ __('backend.projects_edit.assignments_subtitle') }}</div>
            </div>

            <div class="table-wrap">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label-custom" for="student_id">{{ __('backend.projects_edit.student') }}</label>
                        <div class="student-row">
                            <select id="student_id" name="student_id" class="form-select custom-input">
                                <option value="">{{ __('backend.projects_edit.select_student') }}</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}"
                                        {{ (string) old('student_id', $project->student_id) === (string) $student->id ? 'selected' : '' }}>
                                        {{ $student->name }} - {{ $student->email }}
                                    </option>
                                @endforeach
                            </select>

                            <a href="{{ route('register.academic') }}" class="btn btn-success search-btn">
                                <i class="fa fa-user-plus mr-1"></i> {{ __('backend.projects_edit.add_new_student') }}
                            </a>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label-custom" for="supervisor_id">{{ __('backend.projects_edit.supervisor') }}</label>
                        <select id="supervisor_id" name="supervisor_id" class="form-select custom-input">
                            <option value="">{{ __('backend.projects_edit.select_supervisor') }}</option>
                            @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}"
                                    {{ (string) old('supervisor_id', $project->supervisor_id) === (string) $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->name }} - {{ $supervisor->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label-custom" for="manager_id">{{ __('backend.projects_edit.manager') }}</label>
                        <select id="manager_id" name="manager_id" class="form-select custom-input">
                            <option value="">{{ __('backend.projects_edit.select_manager') }}</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}"
                                    {{ (string) old('manager_id', $project->manager_id) === (string) $manager->id ? 'selected' : '' }}>
                                    {{ $manager->name }} - {{ $manager->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label-custom" for="investor_id">{{ __('backend.projects_edit.investor') }}</label>
                        <select id="investor_id" name="investor_id" class="form-select custom-input">
                            <option value="">{{ __('backend.projects_edit.select_investor') }}</option>
                            @foreach($investors as $investor)
                                <option value="{{ $investor->id }}"
                                    {{ (string) old('investor_id', $project->investor_id) === (string) $investor->id ? 'selected' : '' }}>
                                    {{ $investor->name }} - {{ $investor->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <button type="submit" class="btn btn-primary search-btn">
                <i class="fa fa-save mr-1"></i> {{ __('backend.projects_edit.save_changes') }}
            </button>

            <a href="{{ route('admin.projects.show', $project) }}" class="reset-btn">
                <i class="fa fa-eye mr-1"></i> {{ __('backend.projects_edit.view') }}
            </a>

            <a href="{{ route('admin.projects.index') }}" class="reset-btn">
                {{ __('backend.projects_edit.cancel') }}
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.page-header-card, .form-animate').forEach((card, index) => {
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