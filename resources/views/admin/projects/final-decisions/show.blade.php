@extends('layouts.app')

@section('title', __('backend.final_decisions_show.page_title'))

@section('content')
@php
    $finalDecisionClass = match($project->final_decision) {
        'published' => 'badge-published',
        'revision_requested' => 'badge-revision',
        'rejected' => 'badge-rejected',
        default => 'badge-pending',
    };

    $finalDecisionText = match($project->final_decision) {
        'published' => __('backend.final_decisions_show.final_decision_published'),
        'revision_requested' => __('backend.final_decisions_show.final_decision_revision_requested'),
        'rejected' => __('backend.final_decisions_show.final_decision_rejected'),
        default => __('backend.final_decisions_show.final_decision_pending'),
    };

    $statusClass = match($project->status) {
        'published' => 'badge-published',
        'revision_requested' => 'badge-revision',
        'rejected' => 'badge-rejected',
        default => 'badge-pending',
    };
@endphp

<style>
    .manager-decision-page {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        padding: 28px 24px 40px;
    }

    .manager-decision-page * {
        box-sizing: border-box;
    }

    .manager-decision-page .hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
        border-radius: 22px;
        padding: 30px 32px;
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        margin-bottom: 24px;
        border: none;
        overflow: hidden;
    }

    .manager-decision-page .hero-title {
        font-size: clamp(22px, 2vw, 30px);
        font-weight: 900;
        margin-bottom: 10px;
        line-height: 1.45;
        color: #ffffff;
        text-shadow: 0 2px 12px rgba(0,0,0,.22);
        word-break: break-word;
        overflow-wrap: anywhere;
        max-width: 100%;
    }

    .manager-decision-page .hero-text {
        font-size: 14px;
        color: rgba(255,255,255,.92);
        margin-bottom: 0;
        max-width: 780px;
        line-height: 1.7;
    }

    .manager-decision-page .section-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
        margin-bottom: 22px;
        width: 100%;
    }

    .manager-decision-page .section-card .card-header {
        background: #fff;
        padding: 18px 22px;
        border-bottom: 1px solid #eef2f7;
    }

    .manager-decision-page .section-card .card-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 900;
        color: #0f172a;
    }

    .manager-decision-page .section-card .card-body {
        padding: 22px;
        overflow-x: hidden;
    }

    .manager-decision-page .stats-card {
        background: #fff;
        border-radius: 18px;
        padding: 20px 22px;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        height: 100%;
    }

    .manager-decision-page .stats-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .manager-decision-page .stats-value {
        font-size: 30px;
        font-weight: 900;
        color: #020617;
        line-height: 1;
    }

    .manager-decision-page .badge-soft {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        white-space: nowrap;
    }

    .manager-decision-page .badge-published {
        background: #dcfce7;
        color: #166534;
    }

    .manager-decision-page .badge-revision {
        background: #fef3c7;
        color: #92400e;
    }

    .manager-decision-page .badge-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .manager-decision-page .badge-pending {
        background: #e2e8f0;
        color: #334155;
    }

    .manager-decision-page .badge-new-project {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .manager-decision-page .detail-grid {
        margin-left: 0;
        margin-right: 0;
    }

    .manager-decision-page .detail-grid .item {
        padding: 14px 12px;
        border-bottom: 1px dashed #e5e7eb;
        min-width: 0;
    }

    .manager-decision-page .detail-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 800;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .manager-decision-page .detail-value {
        font-size: 15px;
        color: #020617;
        font-weight: 700;
        line-height: 1.7;
        word-break: break-word;
        overflow-wrap: anywhere;
        max-width: 100%;
    }

    .manager-decision-page .review-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        background: #fff;
        margin-bottom: 14px;
        overflow: hidden;
    }

    .manager-decision-page .review-title {
        font-weight: 900;
        font-size: 15px;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .manager-decision-page .review-meta {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 12px;
        line-height: 1.6;
    }

    .manager-decision-page .review-notes {
        color: #334155;
        white-space: pre-line;
        line-height: 1.8;
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .manager-decision-page .decision-form-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
    }

    .manager-decision-page .decision-form-box label {
        font-size: 13px;
        font-weight: 800;
        color: #475569;
        margin-bottom: 8px;
        display: block;
    }

    .manager-decision-page .form-control {
        border-radius: 12px;
        min-height: 46px;
        border-color: #dbe3ef;
    }

    .manager-decision-page textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }

    .manager-decision-page .action-btn {
        border-radius: 12px;
        font-weight: 800;
        padding: 10px 16px;
    }

    .manager-decision-page .project-alert {
        border-radius: 18px;
        border: 0;
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.10);
        background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
        color: #1e3a8a;
        margin-bottom: 22px;
        padding: 18px 20px;
    }

    .manager-decision-page .project-alert-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: #2563eb;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .manager-decision-page .project-alert-title {
        font-size: 15px;
        font-weight: 900;
        margin-bottom: 4px;
        color: #1e3a8a;
    }

    .manager-decision-page .project-alert-text {
        font-size: 14px;
        margin-bottom: 0;
        color: #1e40af;
    }

    .manager-decision-page .project-alert-badge {
        background: #2563eb;
        color: #fff;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 900;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    @media (max-width: 991px) {
        .manager-decision-page {
            padding: 22px 14px 34px;
        }

        .manager-decision-page .hero-card {
            padding: 24px 20px;
        }

        .manager-decision-page .section-card .card-body {
            padding: 18px;
        }
    }
</style>

<div class="container-fluid manager-decision-page">

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 16px;">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($projectAddedNotification) && $projectAddedNotification)
        <div class="project-alert d-flex justify-content-between align-items-center flex-wrap" style="gap: 16px;">
            <div class="d-flex align-items-center" style="gap: 14px;">
                <div class="project-alert-icon">
                    <i class="fas fa-folder-plus"></i>
                </div>

                <div>
                    <div class="project-alert-title">
                        {{ __('backend.final_decisions_show.new_project_notification') }}
                    </div>
                    <p class="project-alert-text">
                        {{ __('backend.final_decisions_show.new_project_notification_text') }}
                    </p>
                </div>
            </div>

            <div>
                <span class="project-alert-badge">{{ __('backend.final_decisions_show.new_project') }}</span>
            </div>
        </div>
    @endif

    <div class="hero-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 18px;">
            <div style="min-width: 0; flex: 1;">
                <div class="hero-title">{{ $project->name }}</div>
                <p class="hero-text">
                    {{ __('backend.final_decisions_show.subtitle') }}
                </p>
            </div>

            <div class="d-flex flex-wrap justify-content-end" style="gap: 10px;">
                @if(isset($projectAddedNotification) && $projectAddedNotification)
                    <span class="badge-soft badge-new-project">
                        {{ __('backend.final_decisions_show.new_project_added') }}
                    </span>
                @endif

                <span class="badge-soft {{ $statusClass }}">
                    {{ __('backend.final_decisions_show.project_status') }} {{ ucfirst(str_replace('_', ' ', $project->status ?? 'draft')) }}
                </span>

                <span class="badge-soft {{ $finalDecisionClass }}">
                    {{ __('backend.final_decisions_show.final_decision') }} {{ $finalDecisionText }}
                </span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-label">{{ __('backend.final_decisions_show.average_score') }}</div>
                <div class="stats-value">{{ $averageScore }}</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-label">{{ __('backend.final_decisions_show.approved_reviews') }}</div>
                <div class="stats-value">{{ $approvedCount }}</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-label">{{ __('backend.final_decisions_show.revision_requests') }}</div>
                <div class="stats-value">{{ $revisionCount }}</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
            <div class="stats-card">
                <div class="stats-label">{{ __('backend.final_decisions_show.rejected_reviews') }}</div>
                <div class="stats-value">{{ $rejectedCount }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5 col-lg-12">
            <div class="section-card">
                <div class="card-header">
                    <h5>{{ __('backend.final_decisions_show.project_overview') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row detail-grid">
                        <div class="col-md-6 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.project_id') }}</div>
                            <div class="detail-value">#{{ $project->project_id }}</div>
                        </div>

                        <div class="col-md-6 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.category') }}</div>
                            <div class="detail-value">{{ $project->category ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.student') }}</div>
                            <div class="detail-value">{{ $project->student?->name ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.student_email') }}</div>
                            <div class="detail-value">{{ $project->student?->email ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.budget') }}</div>
                            <div class="detail-value">{{ $project->budget ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.priority') }}</div>
                            <div class="detail-value">{{ $project->priority ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.current_status') }}</div>
                            <div class="detail-value">{{ ucfirst(str_replace('_', ' ', $project->status ?? 'draft')) }}</div>
                        </div>

                        <div class="col-md-6 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.current_final_decision') }}</div>
                            <div class="detail-value">{{ $finalDecisionText }}</div>
                        </div>

                        <div class="col-md-12 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.project_description') }}</div>
                            <div class="detail-value">{{ $project->description ?? '-' }}</div>
                        </div>

                        <div class="col-md-12 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.last_decision_by') }}</div>
                            <div class="detail-value">
                                @if($project->finalDecisionMaker)
                                    {{ $project->finalDecisionMaker->name }}
                                    @if($project->final_decided_at)
                                        • {{ $project->final_decided_at->format('d/m/Y h:i A') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 item">
                            <div class="detail-label">{{ __('backend.final_decisions_show.last_final_notes') }}</div>
                            <div class="detail-value">{{ $project->final_notes ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-card">
                <div class="card-header">
                    <h5>{{ __('backend.final_decisions_show.final_decision_form') }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.projects.final-decisions.store', $project->project_id) }}">
                        @csrf

                        <div class="decision-form-box mb-3">
                            <label>{{ __('backend.final_decisions_show.final_decision_label') }}</label>
                            <select name="final_decision" class="form-control" required>
                                <option value="">{{ __('backend.final_decisions_show.select_final_decision') }}</option>
                                <option value="published" {{ old('final_decision', $project->final_decision) === 'published' ? 'selected' : '' }}>
                                    {{ __('backend.final_decisions_show.publish_project') }}
                                </option>
                                <option value="revision_requested" {{ old('final_decision', $project->final_decision) === 'revision_requested' ? 'selected' : '' }}>
                                    {{ __('backend.final_decisions_show.request_revision') }}
                                </option>
                                <option value="rejected" {{ old('final_decision', $project->final_decision) === 'rejected' ? 'selected' : '' }}>
                                    {{ __('backend.final_decisions_show.reject_project') }}
                                </option>
                            </select>
                        </div>

                        <div class="decision-form-box mb-3">
                            <label>{{ __('backend.final_decisions_show.manager_notes') }}</label>
                            <textarea
                                name="final_notes"
                                rows="6"
                                class="form-control"
                                placeholder="{{ __('backend.final_decisions_show.manager_notes_placeholder') }}"
                            >{{ old('final_notes', $project->final_notes) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary action-btn">
                            {{ __('backend.final_decisions_show.save_final_decision') }}
                        </button>

                        <a href="{{ route('admin.projects.final-decisions.index') }}" class="btn btn-light action-btn">
                            {{ __('backend.final_decisions_show.back_to_list') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-7 col-lg-12">
            <div class="section-card">
                <div class="card-header">
                    <h5>{{ __('backend.final_decisions_show.supervisor_evaluations') }}</h5>
                </div>
                <div class="card-body">
                    @forelse($project->reviews as $review)
                        @php
                            $decisionClass = match($review->decision) {
                                'approved' => 'badge-published',
                                'revision_requested' => 'badge-revision',
                                'rejected' => 'badge-rejected',
                                default => 'badge-pending',
                            };

                            $decisionText = ucfirst(str_replace('_', ' ', $review->decision ?? 'pending'));
                        @endphp

                        <div class="review-card">
                            <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 10px;">
                                <div style="min-width: 0;">
                                    <div class="review-title">
                                        {{ $review->supervisor?->name ?? __('backend.final_decisions_show.supervisor') }}
                                    </div>
                                    <div class="review-meta">
                                        @if(!is_null($review->score))
                                            {{ __('backend.final_decisions_show.score') }} <strong>{{ $review->score }}/100</strong>
                                        @else
                                            {{ __('backend.final_decisions_show.score') }} <strong>-</strong>
                                        @endif

                                        @if($review->reviewed_at)
                                            • {{ __('backend.final_decisions_show.reviewed') }} {{ $review->reviewed_at->format('d/m/Y h:i A') }}
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <span class="badge-soft {{ $decisionClass }}">
                                        {{ $decisionText }}
                                    </span>
                                </div>
                            </div>

                            <div class="review-notes">
                                {{ $review->notes ?? '-' }}
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">{{ __('backend.final_decisions_show.no_supervisor_reviews_yet') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
@endsection