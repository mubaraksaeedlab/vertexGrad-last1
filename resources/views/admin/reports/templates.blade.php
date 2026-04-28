@extends('layouts.app')

@section('title', __('backend.report_templates.title'))

@section('content')
<style>
    .report-templates-page .hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #2563eb 100%);
        border-radius: 24px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.16);
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .report-templates-page .hero-card::before {
        content: "";
        position: absolute;
        top: -45px;
        right: -45px;
        width: 170px;
        height: 170px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }

    .report-templates-page .hero-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
    }

    .report-templates-page .hero-text {
        color: rgba(255,255,255,0.90);
        font-size: 14px;
        margin-bottom: 0;
        position: relative;
        z-index: 2;
    }

    .report-templates-page .template-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
        padding: 20px;
        height: 100%;
    }

    .report-templates-page .template-title {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
    }

    .report-templates-page .template-meta {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 14px;
        line-height: 1.8;
    }

    .report-templates-page .template-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .report-templates-page .badge-soft {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        background: #eff6ff;
        color: #1d4ed8;
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 report-templates-page">
    <div class="hero-card">
        <div class="hero-title">{{ __('backend.report_templates.page_title') }}</div>
        <p class="hero-text">
            {{ __('backend.report_templates.page_subtitle') }}
        </p>
    </div>

    <div class="row">
        @forelse($templates as $template)
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="template-card">
                    <div class="d-flex justify-content-between align-items-start mb-3" style="gap: 10px;">
                        <div class="template-title">{{ $template->name }}</div>
                        <span class="badge-soft">{{ ucfirst($template->entity) }}</span>
                    </div>

                    <div class="template-meta">
                        <div><strong>{{ __('backend.report_templates.period') }}</strong> {{ ucfirst($template->period) }}</div>
                        <div><strong>{{ __('backend.report_templates.created_by') }}</strong> {{ $template->creator?->name ?? '-' }}</div>
                        <div><strong>{{ __('backend.report_templates.created_at') }}</strong> {{ $template->created_at?->format('Y-m-d h:i A') }}</div>
                    </div>

                    <div class="template-actions">
                        <a href="{{ route('admin.reports.templates.run', $template->id) }}" class="btn btn-primary btn-sm">
                            {{ __('backend.report_templates.run_template') }}
                        </a>

                        <form action="{{ route('admin.reports.templates.delete', $template->id) }}" method="POST" onsubmit="return confirm('{{ __('backend.report_templates.confirm_delete_template') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">{{ __('backend.report_templates.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card-box p-5 text-center" style="border-radius: 20px;">
                    <h5 class="mb-2">{{ __('backend.report_templates.no_saved_templates_yet') }}</h5>
                    <p class="text-muted mb-3">{{ __('backend.report_templates.no_saved_templates_text') }}</p>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-primary">{{ __('backend.report_templates.go_to_reports_center') }}</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $templates->links() }}
    </div>
</div>
@endsection