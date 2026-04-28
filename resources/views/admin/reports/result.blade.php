@extends('layouts.app')

@section('title', $report['title'])

@section('content')
<style>
    .report-result-page .report-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
        border-radius: 24px;
        padding: 30px 32px;
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .report-result-page .report-hero::before {
        content: "";
        position: absolute;
        top: -55px;
        right: -55px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .report-result-page .report-hero::after {
        content: "";
        position: absolute;
        bottom: -70px;
        left: -70px;
        width: 220px;
        height: 220px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }

    .report-result-page .report-hero-content {
        position: relative;
        z-index: 2;
    }

    .report-result-page .report-title {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.4px;
    }

    .report-result-page .report-subtitle {
        font-size: 14px;
        color: rgba(255,255,255,0.90);
        margin-bottom: 0;
    }

    .report-result-page .hero-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 18px;
    }

    .report-result-page .hero-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        background: rgba(255,255,255,0.12);
        color: #fff;
        backdrop-filter: blur(8px);
    }

    .report-result-page .section-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .report-result-page .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .report-result-page .section-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    .report-result-page .section-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .report-result-page .section-body {
        padding: 24px;
    }

    .report-result-page .summary-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 18px;
        height: 100%;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        transition: all 0.2s ease;
    }

    .report-result-page .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.07);
    }

    .report-result-page .summary-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .report-result-page .summary-value {
        font-size: 28px;
        color: #0f172a;
        font-weight: 800;
        line-height: 1;
    }

    .report-result-page .actions-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .report-result-page .btn-action {
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .report-result-page .btn-pdf {
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        border: none;
        color: #fff;
        box-shadow: 0 12px 24px rgba(239, 68, 68, 0.22);
    }

    .report-result-page .btn-pdf:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(239, 68, 68, 0.28);
    }

    .report-result-page .btn-excel {
        background: linear-gradient(135deg, #15803d 0%, #16a34a 100%);
        border: none;
        color: #fff;
        box-shadow: 0 12px 24px rgba(22, 163, 74, 0.22);
    }

    .report-result-page .btn-excel:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(22, 163, 74, 0.28);
    }

    .report-result-page .btn-back {
        background: #fff;
        border: 1px solid #dbe4ee;
        color: #334155;
    }

    .report-result-page .btn-back:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .report-result-page .table-wrap {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
    }

    .report-result-page .custom-table {
        margin-bottom: 0;
        min-width: 1000px;
    }

    .report-result-page .custom-table thead th {
        background: #f8fbff;
        color: #0f172a;
        font-size: 13px;
        font-weight: 800;
        border-bottom: 1px solid #e2e8f0;
        border-top: 0;
        padding: 14px 16px;
        white-space: nowrap;
    }

    .report-result-page .custom-table tbody td {
        padding: 14px 16px;
        font-size: 14px;
        color: #334155;
        border-color: #eef2f7;
        vertical-align: middle;
    }

    .report-result-page .custom-table tbody tr:hover {
        background: #f8fbff;
    }

    .report-result-page .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #64748b;
    }

    .report-result-page .empty-state-icon {
        width: 66px;
        height: 66px;
        margin: 0 auto 16px;
        border-radius: 18px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .report-result-page .empty-state-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .report-result-page .empty-state-text {
        margin: 0;
        font-size: 14px;
        color: #64748b;
    }

    @media (max-width: 767px) {
        .report-result-page .report-hero {
            padding: 24px 20px;
        }

        .report-result-page .report-title {
            font-size: 24px;
        }

        .report-result-page .section-header,
        .report-result-page .section-body {
            padding-left: 16px;
            padding-right: 16px;
        }
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 report-result-page">

    <div class="report-hero">
        <div class="report-hero-content">
            <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
                <div>
                    <div class="report-title">{{ $report['title'] }}</div>
                    <p class="report-subtitle">
                        {{ __('backend.report_result.subtitle') }}
                    </p>

                    <div class="hero-badges">
                        <span class="hero-badge">
                            {{ __('backend.report_result.period') }}: {{ $report['from']->format('Y-m-d') }} → {{ $report['to']->format('Y-m-d') }}
                        </span>

                        <span class="hero-badge">
                            {{ __('backend.report_result.rows') }}: {{ count($report['rows'] ?? []) }}
                        </span>

                        <span class="hero-badge">
                            {{ __('backend.report_result.columns') }}: {{ count($report['headings'] ?? []) }}
                        </span>
                    </div>
                </div>

                <div class="actions-bar">
                    <form method="POST" action="{{ route('admin.reports.export.pdf') }}">
                        @csrf
                        @foreach($filters as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="btn btn-action btn-pdf">
                            <i class="icon-copy dw dw-download mr-1"></i> {{ __('backend.report_result.export_pdf') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.reports.export.excel') }}">
                        @csrf
                        @foreach($filters as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <button type="submit" class="btn btn-action btn-excel">
                            <i class="icon-copy dw dw-download mr-1"></i> {{ __('backend.report_result.export_excel') }}
                        </button>
                    </form>

                    <a href="{{ route('admin.reports.index') }}" class="btn btn-action btn-back">
                        <i class="icon-copy dw dw-left-arrow1 mr-1"></i> {{ __('backend.report_result.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($report['summary']))
        <div class="row mb-4">
            @foreach($report['summary'] as $label => $value)
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="summary-card">
                        <div class="summary-label">{{ $label }}</div>
                        <div class="summary-value">{{ $value }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="section-card">
        <div class="section-header">
            <h4>{{ __('backend.report_result.report_data_table') }}</h4>
            <p>{{ __('backend.report_result.report_data_table_subtitle') }}</p>
        </div>

        <div class="section-body">
            @if(!empty($report['rows']) && count($report['rows']) > 0)
                <div class="table-responsive table-wrap">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                @foreach($report['headings'] as $heading)
                                    <th>{{ ucwords(str_replace('_', ' ', $heading)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report['rows'] as $row)
                                <tr>
                                    @foreach($row as $value)
                                        <td>{{ $value !== null && $value !== '' ? $value : '-' }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="icon-copy dw dw-analytics-21"></i>
                    </div>
                    <div class="empty-state-title">{{ __('backend.report_result.no_report_data_found') }}</div>
                    <p class="empty-state-text">
                        {{ __('backend.report_result.no_report_data_found_text') }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection