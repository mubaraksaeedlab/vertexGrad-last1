@extends('layouts.app')

@section('title', __('backend.investors_email.page_title'))

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
        --success-color: #1cc88a;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --shadow-sm: 0 8px 20px rgba(18, 38, 63, 0.06);
        --shadow-md: 0 14px 36px rgba(18, 38, 63, 0.10);
        --radius-xl: 24px;
        --radius-lg: 20px;
        --radius-md: 16px;
        --radius-sm: 12px;
    }

    body { background: var(--page-bg); }

    .investor-email-page {
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

    .filter-label {
        font-size: 0.82rem;
        color: var(--text-soft);
        font-weight: 700;
        margin-bottom: 8px;
        display: block;
    }

    .form-control.filter-input {
        min-height: 46px;
        border-radius: 14px;
        border: 1px solid #dfe5ef;
        box-shadow: none;
        padding: 12px 14px;
    }

    .form-control.filter-input:focus {
        border-color: rgba(78, 115, 223, 0.5);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.12);
    }

    textarea.filter-input {
        min-height: 180px !important;
        resize: vertical;
    }

    .search-btn {
        min-height: 46px;
        border-radius: 14px;
        font-weight: 700;
        padding: 10px 18px;
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
</style>

<div class="container-fluid investor-email-page">

    @if ($errors->any())
        <div class="alert alert-danger custom-alert mb-4">
            <ul class="mb-0 pl-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.investors_email.heading') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.investors_email.subtitle_prefix') }}
                    <strong>{{ $investor->user?->name ?? __('backend.investors_email.investor_fallback') }}</strong>
                    ({{ $investor->user?->email ?? __('backend.investors_email.no_email') }})
                </p>
            </div>

            <div>
                <a href="{{ route('admin.investors.show', $investor->user_id) }}" class="reset-btn px-4">
                    <i class="fa fa-arrow-left mr-1"></i> {{ __('backend.investors_email.back') }}
                </a>
            </div>
        </div>
    </div>

    <div class="main-panel">
        <div class="panel-head">
            <h2 class="panel-title">
                <i class="fa fa-envelope mr-2"></i>{{ __('backend.investors_email.form_title') }}
            </h2>
            <div class="panel-subtitle">{{ __('backend.investors_email.form_subtitle') }}</div>
        </div>

        <div class="table-wrap">
            <form action="{{ route('admin.investors.email.store', $investor->user_id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="filter-label">{{ __('backend.investors_email.subject') }}</label>
                    <input
                        type="text"
                        name="subject"
                        class="form-control filter-input"
                        value="{{ old('subject') }}"
                        placeholder="{{ __('backend.investors_email.subject_placeholder') }}">
                </div>

                <div class="mb-4">
                    <label class="filter-label">{{ __('backend.investors_email.message') }}</label>
                    <textarea
                        name="message"
                        class="form-control filter-input"
                        placeholder="{{ __('backend.investors_email.message_placeholder') }}">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary search-btn">
                    <i class="fa fa-paper-plane mr-1"></i> {{ __('backend.investors_email.send_email') }}
                </button>
            </form>
        </div>
    </div>

</div>
@endsection