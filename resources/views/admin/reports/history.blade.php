@extends('layouts.app')

@section('title', __('backend.report_history.title'))

@section('content')
<style>
    .report-history-page .hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
        border-radius: 24px;
        padding: 30px 32px;
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .report-history-page .hero-card::before {
        content: "";
        position: absolute;
        top: -45px;
        right: -45px;
        width: 170px;
        height: 170px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }

    .report-history-page .hero-card::after {
        content: "";
        position: absolute;
        bottom: -70px;
        left: -70px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }

    .report-history-page .hero-content {
        position: relative;
        z-index: 2;
    }

    .report-history-page .hero-title {
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.4px;
    }

    .report-history-page .hero-text {
        color: rgba(255,255,255,0.90);
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 0;
        max-width: 780px;
    }

    .report-history-page .mini-stat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 18px;
        height: 100%;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.04);
    }

    .report-history-page .mini-stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .report-history-page .mini-stat-value {
        font-size: 24px;
        color: #0f172a;
        font-weight: 800;
        line-height: 1;
    }

    .report-history-page .section-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .report-history-page .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .report-history-page .section-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    .report-history-page .section-header p {
        margin: 6px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .report-history-page .section-body {
        padding: 24px;
    }

    .report-history-page .export-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        height: 100%;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .report-history-page .export-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #1d4ed8, #2563eb);
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .report-history-page .export-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 34px rgba(15, 23, 42, 0.08);
    }

    .report-history-page .export-card:hover::before {
        opacity: 1;
    }

    .report-history-page .export-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .report-history-page .export-meta {
        font-size: 13px;
        color: #64748b;
        line-height: 1.9;
        margin-bottom: 16px;
    }

    .report-history-page .export-meta strong {
        color: #334155;
        font-weight: 700;
    }

    .report-history-page .badge-soft {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .report-history-page .badge-success-soft {
        background: #dcfce7;
        color: #166534;
    }

    .report-history-page .badge-danger-soft {
        background: #fee2e2;
        color: #991b1b;
    }

    .report-history-page .badge-format {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .report-history-page .badge-source {
        background: #fef3c7;
        color: #92400e;
    }

    .report-history-page .export-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .report-history-page .btn-action {
        border-radius: 12px;
        font-weight: 700;
        padding: 10px 14px;
        transition: all 0.2s ease;
    }

    .report-history-page .btn-download {
        background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
        border: none;
        color: #fff;
        box-shadow: 0 10px 22px rgba(37, 99, 235, 0.20);
    }

    .report-history-page .btn-download:hover {
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 14px 28px rgba(37, 99, 235, 0.28);
    }

    .report-history-page .btn-delete {
        border: 1px solid #fecaca;
        background: #fff5f5;
        color: #dc2626;
    }

    .report-history-page .btn-delete:hover {
        background: #fee2e2;
        color: #b91c1c;
    }

    .report-history-page .empty-state {
        text-align: center;
        padding: 52px 24px;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        border: 1px dashed #cbd5e1;
    }

    .report-history-page .empty-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 16px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eff6ff;
        color: #2563eb;
        font-size: 26px;
    }

    .report-history-page .empty-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .report-history-page .empty-text {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 18px;
    }

    .custom-pagination .page-box {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        color: #334155;
        background: #fff;
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
        transition: all 0.2s ease;
    }

    .custom-pagination .page-box:hover {
        background: #1d4ed8;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(29, 78, 216, 0.25);
    }

    .custom-pagination .page-box.active {
        background: linear-gradient(135deg, #1d4ed8, #2563eb);
        color: #fff;
        border: none;
        box-shadow: 0 10px 24px rgba(37, 99, 235, 0.35);
    }

    .custom-pagination .page-box.disabled {
        background: #f1f5f9;
        color: #94a3b8;
        pointer-events: none;
    }

    @media (max-width: 767px) {
        .report-history-page .hero-card {
            padding: 24px 20px;
        }

        .report-history-page .hero-title {
            font-size: 24px;
        }

        .report-history-page .section-header,
        .report-history-page .section-body {
            padding-left: 16px;
            padding-right: 16px;
        }
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 report-history-page">
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 16px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 16px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="hero-card">
        <div class="hero-content">
            <div class="hero-title">{{ __('backend.report_history.page_title') }}</div>
            <p class="hero-text">
                {{ __('backend.report_history.page_subtitle') }}
            </p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mini-stat">
                <div class="mini-stat-label">{{ __('backend.report_history.total_exports') }}</div>
                <div class="mini-stat-value">{{ $exports->total() }}</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mini-stat">
                <div class="mini-stat-label">{{ __('backend.report_history.successful') }}</div>
                <div class="mini-stat-value">{{ $exports->where('status', 'completed')->count() }}</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mini-stat">
                <div class="mini-stat-label">{{ __('backend.report_history.failed') }}</div>
                <div class="mini-stat-value">{{ $exports->where('status', 'failed')->count() }}</div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="mini-stat">
                <div class="mini-stat-label">{{ __('backend.report_history.this_page') }}</div>
                <div class="mini-stat-value">{{ $exports->count() }}</div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-header">
            <h4>{{ __('backend.report_history.generated_report_exports') }}</h4>
            <p>{{ __('backend.report_history.generated_report_exports_subtitle') }}</p>
        </div>

        <div class="section-body">
            <div class="row">
                @forelse($exports as $export)
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="export-card">
                            <div class="d-flex justify-content-between align-items-start mb-3" style="gap: 10px;">
                                <div>
                                    <div class="export-title">
                                        {{ $export->template?->name ?? __('backend.report_history.report_export') }}
                                    </div>
                                    <span class="badge-soft badge-format">{{ strtoupper($export->format ?? 'PDF') }}</span>
                                </div>

                                <span class="badge-soft {{ $export->status === 'completed' ? 'badge-success-soft' : 'badge-danger-soft' }}">
                                    {{ ucfirst($export->status) }}
                                </span>
                            </div>

                            <div class="export-meta">
                                <div>
                                    <strong>{{ __('backend.report_history.source') }}</strong>
                                    @if($export->scheduled_report_id)
                                        <span class="badge-soft badge-source">{{ __('backend.report_history.scheduled') }}</span>
                                    @else
                                        <span class="badge-soft badge-source">{{ __('backend.report_history.manual') }}</span>
                                    @endif
                                </div>

                                <div><strong>{{ __('backend.report_history.template') }}</strong> {{ $export->template?->name ?? '-' }}</div>
                                <div><strong>{{ __('backend.report_history.generated_by') }}</strong> {{ $export->user?->name ?? '-' }}</div>
                                <div>
                                    <strong>{{ __('backend.report_history.generated_at') }}</strong>
                                    {{ $export->generated_at ? $export->generated_at->timezone(config('app.timezone'))->format('Y-m-d h:i A') : '-' }}
                                </div>
                                <div><strong>{{ __('backend.report_history.file') }}</strong> {{ $export->file_path ? basename($export->file_path) : '-' }}</div>
                            </div>

                            <div class="export-actions">
                                @if($export->file_path && $export->status === 'completed')
                                    <a href="{{ route('admin.reports.history.download', $export->id) }}" class="btn btn-action btn-download btn-sm">
                                        {{ __('backend.report_history.download') }}
                                    </a>
                                @endif

                                <form action="{{ route('admin.reports.history.delete', $export->id) }}" method="POST" onsubmit="return confirm('{{ __('backend.report_history.confirm_delete_export') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-delete btn-sm">
                                        {{ __('backend.report_history.delete') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="icon-copy dw dw-file-111"></i>
                            </div>
                            <div class="empty-title">{{ __('backend.report_history.no_export_history_yet') }}</div>
                            <div class="empty-text">
                                {{ __('backend.report_history.no_export_history_text') }}
                            </div>
                            <a href="{{ route('admin.reports.index') }}" class="btn btn-primary">
                                {{ __('backend.report_history.go_to_reports_center') }}
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            @if ($exports->hasPages())
                <div class="custom-pagination mt-4 d-flex justify-content-center flex-wrap" style="gap: 8px;">
                    @if ($exports->onFirstPage())
                        <span class="page-box disabled">1</span>
                    @else
                        <a href="{{ $exports->previousPageUrl() }}" class="page-box">{{ $exports->currentPage() - 1 }}</a>
                    @endif

                    @foreach ($exports->getUrlRange(1, $exports->lastPage()) as $page => $url)
                        @if ($page == $exports->currentPage())
                            <span class="page-box active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-box">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($exports->hasMorePages())
                        <a href="{{ $exports->nextPageUrl() }}" class="page-box">{{ $exports->currentPage() + 1 }}</a>
                    @else
                        <span class="page-box disabled">{{ $exports->currentPage() }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection