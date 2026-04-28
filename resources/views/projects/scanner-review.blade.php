@extends('layouts.app')

@section('title', __('backend.scanner_review.page_title'))

@section('content')
<style>
    :root {
        --page-bg: #f5f7fb;
        --card-bg: #ffffff;
        --text-main: #172033;
        --text-soft: #7b8497;
        --border-color: #e8ecf4;
        --primary-color: #4e73df;
        --primary-soft: rgba(78, 115, 223, 0.10);
        --shadow-sm: 0 8px 20px rgba(18, 38, 63, 0.06);
        --radius-xl: 24px;
    }

    body { background: var(--page-bg); }

    .scanner-review-page {
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

    .info-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 18px;
        height: 100%;
    }

    .info-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 8px;
    }

    .info-value {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        word-break: break-word;
    }

    .note-box {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e40af;
        border-radius: 16px;
        padding: 18px;
        line-height: 1.8;
    }

    .action-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    @media (max-width: 991px) {
        .page-header-card { padding: 22px 20px; }
        .panel-head, .table-wrap { padding-left: 18px; padding-right: 18px; }
    }

    @media (max-width: 576px) {
        .page-title { font-size: 1.3rem; }
    }
</style>

<div class="container-fluid scanner-review-page">

    @if(session('error'))
        <div class="alert alert-danger custom-alert mb-4">{{ session('error') }}</div>
    @endif

    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.scanner_review.heading') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.scanner_review.subtitle') }}
                </p>
            </div>

            <div>
                <a href="{{ route('admin.projects.edit', $project) }}" class="reset-btn">
                    <i class="fa fa-arrow-left mr-1"></i> {{ __('backend.scanner_review.back_to_edit') }}
                </a>
            </div>
        </div>
    </div>

    <div class="main-panel form-animate">
        <div class="panel-head">
            <h2 class="panel-title">{{ __('backend.scanner_review.project_scan_information') }}</h2>
            <div class="panel-subtitle">{{ __('backend.scanner_review.project_scan_information_subtitle') }}</div>
        </div>

        <div class="table-wrap">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="info-box">
                        <div class="info-label">{{ __('backend.scanner_review.project_name') }}</div>
                        <div class="info-value">{{ $project->name ?? '—' }}</div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-box">
                        <div class="info-label">{{ __('backend.scanner_review.student_name') }}</div>
                        <div class="info-value">{{ $project->student->name ?? '—' }}</div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="info-box">
                        <div class="info-label">{{ __('backend.scanner_review.student_email') }}</div>
                        <div class="info-value">{{ $project->student->email ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="note-box mt-3">
                {!! __('backend.scanner_review.note_box') !!}
            </div>
        </div>
    </div>

    <form action="{{ route('admin.projects.startScan', $project) }}" method="POST">
        @csrf

        <div class="action-bar">
            <button type="submit" class="btn btn-success search-btn">
                <i class="fa fa-shield-alt mr-1"></i> {{ __('backend.scanner_review.start_technical_scan') }}
            </button>

            <a href="{{ route('admin.projects.edit', $project) }}" class="reset-btn">
                {{ __('backend.scanner_review.cancel') }}
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