@extends('layouts.app')

@section('title', __('backend.reports_center.title'))

@section('content')
<style>
    .reports-page {
        --rp-primary: #2563eb;
        --rp-primary-dark: #1d4ed8;
        --rp-secondary: #0f172a;
        --rp-muted: #64748b;
        --rp-border: #e2e8f0;
        --rp-soft: #f8fafc;
        --rp-soft-blue: #eff6ff;
        --rp-success: #16a34a;
        --rp-warning: #f59e0b;
        --rp-purple: #7c3aed;
        --rp-danger: #dc2626;
    }

    .reports-page * {
        box-sizing: border-box;
    }

    .reports-page .reports-hero {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 24%),
            radial-gradient(circle at bottom left, rgba(255,255,255,0.12), transparent 20%),
            linear-gradient(135deg, #0f172a 0%, #1e3a8a 38%, #2563eb 72%, #60a5fa 100%);
        border-radius: 28px;
        padding: 34px 34px 28px;
        color: #fff;
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.18);
        margin-bottom: 24px;
        border: 1px solid rgba(255,255,255,0.08);
    }

    .reports-page .reports-hero::before {
        content: "";
        position: absolute;
        width: 240px;
        height: 240px;
        right: -70px;
        top: -70px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .reports-page .reports-hero::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        left: -50px;
        bottom: -60px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }

    .reports-page .reports-hero-content {
        position: relative;
        z-index: 2;
    }

    .reports-page .reports-hero-top {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .reports-page .reports-hero-title {
        font-size: 32px;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .reports-page .reports-hero-text {
        max-width: 760px;
        color: rgba(255,255,255,0.90);
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .reports-page .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.16);
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        backdrop-filter: blur(6px);
        margin-bottom: 14px;
    }

    .reports-page .hero-stats {
        margin-top: 24px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        position: relative;
        z-index: 2;
    }

    .reports-page .hero-stat {
        background: rgba(255,255,255,0.10);
        border: 1px solid rgba(255,255,255,0.14);
        border-radius: 18px;
        padding: 16px 18px;
        backdrop-filter: blur(8px);
        min-height: 92px;
    }

    .reports-page .hero-stat-label {
        color: rgba(255,255,255,0.75);
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .reports-page .hero-stat-value {
        font-size: 24px;
        font-weight: 800;
        color: #fff;
        line-height: 1;
        margin-bottom: 8px;
    }

    .reports-page .hero-stat-sub {
        color: rgba(255,255,255,0.82);
        font-size: 12px;
    }

    .reports-page .section-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.07);
        border: 1px solid #eef2f7;
        margin-bottom: 24px;
        overflow: hidden;
    }

    .reports-page .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        padding: 22px 26px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .reports-page .section-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        color: var(--rp-secondary);
    }

    .reports-page .section-subtitle {
        margin-top: 6px;
        color: var(--rp-muted);
        font-size: 13px;
    }

    .reports-page .section-body {
        padding: 26px;
    }

    .reports-page .quick-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .reports-page .quick-chip {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 14px;
        border-radius: 12px;
        border: 1px solid var(--rp-border);
        background: #fff;
        color: var(--rp-secondary);
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
        transition: all .2s ease;
    }

    .reports-page .quick-chip:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(37, 99, 235, 0.10);
        border-color: #bfdbfe;
        color: var(--rp-primary);
    }

    .reports-page .form-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .reports-page .form-section-title h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--rp-secondary);
    }

    .reports-page .form-section-title span {
        font-size: 12px;
        font-weight: 700;
        color: var(--rp-muted);
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 6px 10px;
        border-radius: 999px;
    }

    .reports-page .input-card {
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        border: 1px solid #e8eef5;
        border-radius: 18px;
        padding: 14px;
        height: 100%;
        transition: all .2s ease;
    }

    .reports-page .input-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
    }

    .reports-page .input-card label {
        display: block;
        font-size: 12px;
        font-weight: 800;
        color: var(--rp-secondary);
        margin-bottom: 9px;
        letter-spacing: 0.2px;
    }

    .reports-page .form-control,
    .reports-page .form-select,
    .reports-page select,
    .reports-page input[type="text"],
    .reports-page input[type="date"] {
        border-radius: 14px !important;
        min-height: 48px;
        border: 1px solid #dbe4ee !important;
        background: #fff !important;
        box-shadow: none !important;
        color: #0f172a;
        font-size: 14px;
        font-weight: 500;
        transition: all .2s ease;
    }

    .reports-page .form-control:focus,
    .reports-page .form-select:focus,
    .reports-page select:focus,
    .reports-page input[type="text"]:focus,
    .reports-page input[type="date"]:focus {
        border-color: #93c5fd !important;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.10) !important;
    }

    .reports-page .helper-text {
        margin-top: 8px;
        color: #94a3b8;
        font-size: 12px;
        line-height: 1.5;
    }

    .reports-page .columns-wrapper {
        background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 22px;
        padding: 18px;
    }

    .reports-page .columns-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .reports-page .columns-head h6 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--rp-secondary);
    }

    .reports-page .columns-note {
        color: var(--rp-muted);
        font-size: 12px;
        font-weight: 600;
    }

    .reports-page .column-option {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
        border: 1px solid #dce6f1;
        border-radius: 16px;
        padding: 14px 14px;
        cursor: pointer;
        background: #fff;
        min-height: 62px;
        transition: all .22s ease;
        font-weight: 700;
        color: #0f172a;
        overflow: hidden;
    }

    .reports-page .column-option::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.06), rgba(59, 130, 246, 0.00));
        opacity: 0;
        transition: opacity .22s ease;
    }

    .reports-page .column-option:hover {
        border-color: #93c5fd;
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(37, 99, 235, 0.08);
    }

    .reports-page .column-option:hover::before {
        opacity: 1;
    }

    .reports-page .column-option input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #2563eb;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
    }

    .reports-page .column-option span {
        position: relative;
        z-index: 2;
        font-size: 13px;
    }

    .reports-page .template-box {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border: 1px dashed #cbd5e1;
        border-radius: 20px;
        padding: 18px;
    }

    .reports-page .template-box .title {
        font-size: 15px;
        font-weight: 800;
        color: var(--rp-secondary);
        margin-bottom: 10px;
    }

    .reports-page .report-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px solid #edf2f7;
    }

    .reports-page .btn-report-primary,
    .reports-page .btn-report-secondary,
    .reports-page .btn-report-light,
    .reports-page .btn-report-danger {
        border: none;
        border-radius: 14px;
        padding: 12px 18px;
        font-size: 14px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        text-decoration: none !important;
        transition: all .22s ease;
        box-shadow: none;
    }

    .reports-page .btn-report-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #fff;
        box-shadow: 0 14px 24px rgba(37, 99, 235, 0.22);
    }

    .reports-page .btn-report-primary:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 18px 32px rgba(37, 99, 235, 0.28);
    }

    .reports-page .btn-report-secondary {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        color: #fff;
        box-shadow: 0 14px 24px rgba(124, 58, 237, 0.20);
    }

    .reports-page .btn-report-secondary:hover {
        color: #fff;
        transform: translateY(-2px);
    }

    .reports-page .btn-report-light {
        background: #fff;
        color: var(--rp-secondary);
        border: 1px solid #dbe4ee;
    }

    .reports-page .btn-report-light:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
        border-color: #bfdbfe;
        color: var(--rp-primary);
    }

    .reports-page .btn-report-danger {
        background: #fff5f5;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .reports-page .btn-report-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(220, 38, 38, 0.10);
        color: #b91c1c;
    }

    .reports-page .mini-info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 14px;
        margin-top: 22px;
    }

    .reports-page .mini-info-card {
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 16px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
    }

    .reports-page .mini-info-card .mini-label {
        color: var(--rp-muted);
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .reports-page .mini-info-card .mini-value {
        color: var(--rp-secondary);
        font-size: 17px;
        font-weight: 800;
        line-height: 1.2;
    }

    @media (max-width: 991px) {
        .reports-page .reports-hero {
            padding: 26px 22px 24px;
        }

        .reports-page .reports-hero-title {
            font-size: 27px;
        }

        .reports-page .section-header,
        .reports-page .section-body {
            padding: 20px;
        }
    }

    @media (max-width: 767px) {
        .reports-page .reports-hero-title {
            font-size: 24px;
        }

        .reports-page .report-actions {
            flex-direction: column;
        }

        .reports-page .report-actions > * {
            width: 100%;
            justify-content: center;
        }

        .reports-page .quick-actions {
            width: 100%;
        }

        .reports-page .quick-chip {
            flex: 1 1 auto;
        }
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 reports-page">

    <div class="reports-hero">
        <div class="reports-hero-content">
            <div class="reports-hero-top">
                <div>
                    <div class="hero-badge">
                        <i class="icon-copy fa fa-line-chart"></i>
                        {{ __('backend.reports_center.advanced_reporting_workspace') }}
                    </div>

                    <div class="reports-hero-title">{{ __('backend.reports_center.page_title') }}</div>

                    <p class="reports-hero-text">
                        {{ __('backend.reports_center.page_subtitle') }}
                    </p>
                </div>

                <div class="quick-actions">
                    <a href="{{ route('admin.reports.templates') }}" class="quick-chip">
                        <i class="fa fa-clone"></i> {{ __('backend.reports_center.my_templates') }}
                    </a>
                    <a href="{{ route('admin.reports.scheduled') }}" class="quick-chip">
                        <i class="fa fa-clock-o"></i> {{ __('backend.reports_center.scheduled_reports') }}
                    </a>
                    <a href="{{ route('admin.reports.history') }}" class="quick-chip">
                        <i class="fa fa-history"></i> {{ __('backend.reports_center.report_history') }}
                    </a>
                </div>
            </div>

            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-label">{{ __('backend.reports_center.flexible_entities') }}</div>
                    <div class="hero-stat-value">{{ count($entities ?? []) }}</div>
                    <div class="hero-stat-sub">{{ __('backend.reports_center.flexible_entities_sub') }}</div>
                </div>

                <div class="hero-stat">
                    <div class="hero-stat-label">{{ __('backend.reports_center.time_periods') }}</div>
                    <div class="hero-stat-value">{{ count($periods ?? []) }}</div>
                    <div class="hero-stat-sub">{{ __('backend.reports_center.time_periods_sub') }}</div>
                </div>

                <div class="hero-stat">
                    <div class="hero-stat-label">{{ __('backend.reports_center.export_ready') }}</div>
                    <div class="hero-stat-value">{{ __('backend.reports_center.export_ready_value') }}</div>
                    <div class="hero-stat-sub">{{ __('backend.reports_center.export_ready_sub') }}</div>
                </div>

                <div class="hero-stat">
                    <div class="hero-stat-label">{{ __('backend.reports_center.automation') }}</div>
                    <div class="hero-stat-value">{{ __('backend.reports_center.automation_value') }}</div>
                    <div class="hero-stat-sub">{{ __('backend.reports_center.automation_sub') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">
            <div>
                <h4>{{ __('backend.reports_center.build_report') }}</h4>
                <div class="section-subtitle">
                    {{ __('backend.reports_center.build_report_subtitle') }}
                </div>
            </div>
        </div>

        <div class="section-body">
            <form method="POST" action="{{ route('admin.reports.preview') }}">
                @csrf

                <div class="form-section-title">
                    <h5>{{ __('backend.reports_center.report_configuration') }}</h5>
                    <span>{{ __('backend.reports_center.step_1') }}</span>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="input-card">
                            <label>{{ __('backend.reports_center.entity') }}</label>
                            <select name="entity" id="entity-select" class="form-control" required>
                                @foreach($entities as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="helper-text">
                                {{ __('backend.reports_center.entity_helper') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="input-card">
                            <label>{{ __('backend.reports_center.period') }}</label>
                            <select name="period" id="period-select" class="form-control" required>
                                @foreach($periods as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="helper-text">
                                {{ __('backend.reports_center.period_helper') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="mini-info-card h-100">
                            <div class="mini-label">{{ __('backend.reports_center.quick_note') }}</div>
                            <div class="mini-value">{{ __('backend.reports_center.quick_note_text') }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3 custom-date-field" style="display:none;">
                        <div class="input-card">
                            <label>{{ __('backend.reports_center.from_date') }}</label>
                            <input type="date" name="from" class="form-control">
                            <div class="helper-text">{{ __('backend.reports_center.from_date_helper') }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3 custom-date-field" style="display:none;">
                        <div class="input-card">
                            <label>{{ __('backend.reports_center.to_date') }}</label>
                            <input type="date" name="to" class="form-control">
                            <div class="helper-text">{{ __('backend.reports_center.to_date_helper') }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-section-title mt-2">
                    <h5>{{ __('backend.reports_center.advanced_filters') }}</h5>
                    <span>{{ __('backend.reports_center.step_2') }}</span>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="input-card">
                            <label>{{ __('backend.reports_center.status') }}</label>
                            <input type="text" name="status" class="form-control" placeholder="{{ __('backend.reports_center.status_placeholder') }}">
                            <div class="helper-text">{{ __('backend.reports_center.status_helper') }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="input-card">
                            <label>{{ __('backend.reports_center.final_decision') }}</label>
                            <input type="text" name="final_decision" class="form-control" placeholder="{{ __('backend.reports_center.final_decision_placeholder') }}">
                            <div class="helper-text">{{ __('backend.reports_center.final_decision_helper') }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="input-card">
                            <label>{{ __('backend.reports_center.category') }}</label>
                            <input type="text" name="category" class="form-control" placeholder="{{ __('backend.reports_center.category_placeholder') }}">
                            <div class="helper-text">{{ __('backend.reports_center.category_helper') }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="input-card">
                            <label>{{ __('backend.reports_center.student_name') }}</label>
                            <input type="text" name="student_name" class="form-control" placeholder="{{ __('backend.reports_center.student_name_placeholder') }}">
                            <div class="helper-text">{{ __('backend.reports_center.student_name_helper') }}</div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="input-card">
                            <label>{{ __('backend.reports_center.investor_name') }}</label>
                            <input type="text" name="investor_name" class="form-control" placeholder="{{ __('backend.reports_center.investor_name_placeholder') }}">
                            <div class="helper-text">{{ __('backend.reports_center.investor_name_helper') }}</div>
                        </div>
                    </div>
                </div>

                <div class="form-section-title mt-2">
                    <h5>{{ __('backend.reports_center.visible_columns') }}</h5>
                    <span>{{ __('backend.reports_center.step_3') }}</span>
                </div>

                @foreach($availableColumns as $entityKey => $columns)
                    <div class="columns-group" data-entity="{{ $entityKey }}" style="{{ $entityKey !== 'projects' ? 'display:none;' : '' }}">
                        <div class="columns-wrapper mb-4">
                            <div class="columns-head">
                                <div>
                                    <h6>{{ ucfirst($entityKey) }} {{ __('backend.reports_center.columns') }}</h6>
                                    <div class="columns-note">
                                        {{ __('backend.reports_center.columns_note') }}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($columns as $key => $label)
                                    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                                        <label class="column-option">
                                            <input type="checkbox" name="columns[]" value="{{ $key }}">
                                            <span>{{ $label }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="form-section-title mt-2">
                    <h5>{{ __('backend.reports_center.template_settings') }}</h5>
                    <span>{{ __('backend.reports_center.optional') }}</span>
                </div>

                <div class="template-box">
                    <div class="row align-items-end">
                        <div class="col-md-5 mb-3 mb-md-0">
                            <div class="title">{{ __('backend.reports_center.save_as_reusable_template') }}</div>
                            <div class="helper-text" style="margin-top:0;">
                                {{ __('backend.reports_center.save_as_reusable_template_text') }}
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label style="font-size:12px;font-weight:800;color:#0f172a;margin-bottom:8px;display:block;">
                                {{ __('backend.reports_center.template_name') }}
                            </label>
                            <input type="text" name="template_name" class="form-control" placeholder="{{ __('backend.reports_center.template_name_placeholder') }}">
                        </div>

                        <div class="col-md-2 mt-3 mt-md-0">
                            <button type="submit"
                                    formaction="{{ route('admin.reports.templates.save') }}"
                                    class="btn btn-report-secondary w-100 justify-content-center">
                                <i class="fa fa-save"></i> {{ __('backend.reports_center.save') }}
                            </button>
                        </div>
                    </div>
                </div>

                <div class="report-actions">
                    <button type="submit" class="btn btn-report-primary">
                        <i class="fa fa-eye"></i> {{ __('backend.reports_center.preview_report') }}
                    </button>

                    <button type="reset" class="btn btn-report-light">
                        <i class="fa fa-refresh"></i> {{ __('backend.reports_center.reset_fields') }}
                    </button>

                    <a href="{{ route('admin.reports.templates') }}" class="btn btn-report-light">
                        <i class="fa fa-clone"></i> {{ __('backend.reports_center.my_templates') }}
                    </a>

                    <a href="{{ route('admin.reports.scheduled') }}" class="btn btn-report-light">
                        <i class="fa fa-clock-o"></i> {{ __('backend.reports_center.scheduled_reports') }}
                    </a>

                    <a href="{{ route('admin.reports.history') }}" class="btn btn-report-light">
                        <i class="fa fa-history"></i> {{ __('backend.reports_center.report_history') }}
                    </a>
                </div>

                <div class="mini-info-grid">
                    <div class="mini-info-card">
                        <div class="mini-label">{{ __('backend.reports_center.preview_mode') }}</div>
                        <div class="mini-value">{{ __('backend.reports_center.preview_mode_text') }}</div>
                    </div>

                    <div class="mini-info-card">
                        <div class="mini-label">{{ __('backend.reports_center.template_reuse') }}</div>
                        <div class="mini-value">{{ __('backend.reports_center.template_reuse_text') }}</div>
                    </div>

                    <div class="mini-info-card">
                        <div class="mini-label">{{ __('backend.reports_center.automation_ready') }}</div>
                        <div class="mini-value">{{ __('backend.reports_center.automation_ready_text') }}</div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const entitySelect = document.getElementById('entity-select');
    const periodSelect = document.getElementById('period-select');
    const groups = document.querySelectorAll('.columns-group');
    const customDates = document.querySelectorAll('.custom-date-field');

    function toggleGroups() {
        groups.forEach(group => {
            group.style.display = group.dataset.entity === entitySelect.value ? 'block' : 'none';
        });
    }

    function toggleDates() {
        const isCustom = periodSelect.value === 'custom';
        customDates.forEach(field => {
            field.style.display = isCustom ? 'block' : 'none';
        });
    }

    entitySelect.addEventListener('change', toggleGroups);
    periodSelect.addEventListener('change', toggleDates);

    toggleGroups();
    toggleDates();
});
</script>
@endsection