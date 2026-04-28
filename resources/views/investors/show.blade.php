@extends('layouts.app')

@section('title', __('backend.investors_show.page_title'))

@section('content')
<style>
    .investor-show-page {
        --page-bg: #f5f7fb;
        --card-bg: #ffffff;
        --text-main: #172033;
        --text-soft: #7b8497;
        --border-color: #e8ecf4;
        --primary-color: #4e73df;
        --primary-soft: rgba(78, 115, 223, 0.10);
        --info-color: #36b9cc;
        --info-soft: rgba(54, 185, 204, 0.12);
        --success-soft: rgba(28, 200, 138, 0.12);
        --warning-soft: rgba(246, 194, 62, 0.14);
        --danger-soft: rgba(231, 74, 59, 0.12);
        --shadow-sm: 0 8px 20px rgba(18, 38, 63, 0.06);
    }

    .investor-show-page .page-header-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9fbff 100%);
        border-radius: 24px;
        padding: 26px 28px;
        color: var(--text-main);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
    }

    .investor-show-page .page-header-card h3 {
        margin: 0;
        font-weight: 800;
        color: var(--text-main);
        font-size: 30px;
    }

    .investor-show-page .page-header-card p {
        margin: 10px 0 0;
        color: var(--text-soft);
        font-size: 15px;
    }

    .investor-show-page .info-card,
    .investor-show-page .section-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
        height: 100%;
    }

    .investor-show-page .section-header {
        padding: 18px 22px;
        border-bottom: 1px solid #eef2f7;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .investor-show-page .section-header .title-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .investor-show-page .section-body {
        padding: 22px;
    }

    .investor-show-page .info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .investor-show-page .info-item {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 16px;
    }

    .investor-show-page .info-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .investor-show-page .info-value {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        word-break: break-word;
    }

    .investor-show-page .badge-soft {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .investor-show-page .badge-status-active,
    .investor-show-page .badge-funding-approved {
        background: #ecfdf5;
        color: #15803d;
    }

    .investor-show-page .badge-status-inactive,
    .investor-show-page .badge-funding-rejected {
        background: #fef2f2;
        color: #dc2626;
    }

    .investor-show-page .badge-funding-requested {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .investor-show-page .badge-funding-interested {
        background: #fff7ed;
        color: #c2410c;
    }

    .investor-show-page .badge-default {
        background: #f1f5f9;
        color: #475569;
    }

    .investor-show-page .list-clean {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .investor-show-page .list-clean li {
        padding: 14px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .investor-show-page .list-clean li:last-child {
        border-bottom: none;
    }

    .investor-show-page .record-title {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 4px;
    }

    .investor-show-page .record-meta {
        font-size: 12px;
        color: #64748b;
    }

    .investor-show-page .record-text {
        color: #334155;
        margin-top: 6px;
        line-height: 1.7;
        word-break: break-word;
    }

    .investor-show-page .modern-table {
        margin-bottom: 0;
        width: 100%;
        table-layout: auto;
    }

    .investor-show-page .modern-table thead th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 10px;
        font-size: 13px;
        white-space: nowrap;
    }

    .investor-show-page .modern-table tbody td {
        padding: 12px 10px;
        border-color: #f1f5f9;
        font-size: 13px;
        vertical-align: middle;
    }

    .investor-show-page .modern-table tbody tr:hover {
        background: #fafcff;
    }

    .investor-show-page .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }

    .investor-show-page .empty-state i {
        font-size: 38px;
        color: #cbd5e1;
        margin-bottom: 10px;
    }

    .investor-show-page .btn-back {
        background: #eef2f8;
        border: none;
        color: #0f172a;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        text-decoration: none;
    }

    .investor-show-page .btn-edit,
    .investor-show-page .btn-primary-custom {
        background: linear-gradient(135deg, #4e73df, #6f8df3);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        text-decoration: none;
    }

    .investor-show-page .btn-outline-danger-custom {
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 10px;
    }

    .investor-show-page .form-control {
        border-radius: 12px;
        border: 1px solid #dbe4f0;
        box-shadow: none;
    }

    .investor-show-page .name-link {
        text-decoration: none;
        color: #0f172a;
        font-weight: 700;
    }

    .investor-show-page .mini-summary-card {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 16px;
        padding: 16px;
        height: 100%;
    }

    .investor-show-page .icon-delete-btn {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(231, 74, 59, 0.12);
        color: #dc2626;
    }

    .investor-show-page .auto-resize {
        min-height: 120px;
    }

    @media (max-width: 991px) {
        .investor-show-page .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 investor-show-page">
    <div class="min-height-200px">
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 14px;">
                {{ session('error') }}
            </div>
        @endif

        @php
            $statusClass = match($investor->user?->status) {
                'active' => 'badge-status-active',
                'inactive' => 'badge-status-inactive',
                default => 'badge-default',
            };

            $fundingStats = [
                'interested' => $projectInvestments->where('pivot.status', 'interested')->count(),
                'requested' => $projectInvestments->where('pivot.status', 'requested')->count(),
                'approved' => $projectInvestments->where('pivot.status', 'approved')->count(),
                'rejected' => $projectInvestments->where('pivot.status', 'rejected')->count(),
            ];
        @endphp

        <div class="page-header-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                <div>
                    <h3>{{ $investor->user?->name ?? __('backend.investors_show.page_title') }}</h3>
                    <p>{{ __('backend.investors_show.header_subtitle') }}</p>
                </div>

                <div class="d-flex flex-wrap" style="gap: 10px;">
                    <a href="{{ route('admin.investors.index') }}" class="btn-back"><i class="fa fa-arrow-left mr-1"></i> {{ __('backend.investors_show.back') }}</a>
                    <a href="{{ route('admin.investment-requests.index', ['search' => $investor->user?->email]) }}" class="btn-back"><i class="fa fa-hand-holding-usd mr-1"></i> {{ __('backend.investors_show.requests') }}</a>
                    <a href="{{ route('admin.investors.report', $investor->user_id) }}" class="btn-back"><i class="fa fa-chart-bar mr-1"></i> {{ __('backend.investors_show.report') }}</a>
                    <a href="{{ route('admin.investors.meetings.index', $investor->user_id) }}" class="btn-back"><i class="fa fa-calendar-alt mr-1"></i> {{ __('backend.investors_show.meetings') }}</a>
                    <a href="{{ route('admin.investors.contracts.index', $investor->user_id) }}" class="btn-back"><i class="fa fa-file-contract mr-1"></i> {{ __('backend.investors_show.contracts') }}</a>
                    <a href="{{ route('admin.investors.email.create', $investor->user_id) }}" class="btn-back"><i class="fa fa-envelope mr-1"></i> {{ __('backend.investors_show.email') }}</a>
                    <a href="{{ route('admin.investors.preferences.edit', $investor->user_id) }}" class="btn-back"><i class="fa fa-sliders-h mr-1"></i> {{ __('backend.investors_show.preferences') }}</a>
                    <a href="{{ route('admin.investors.reminders.index', $investor->user_id) }}" class="btn-back"><i class="fa fa-bell mr-1"></i> {{ __('backend.investors_show.reminders') }}</a>
                    <a href="{{ route('admin.investors.notify.create',$investor->user_id) }}" class="btn-back"><i class="fa fa-bell mr-1"></i> {{ __('backend.investors_show.notify') }}</a>
                    <a href="{{ route('admin.investors.edit', $investor->user_id) }}" class="btn-edit"><i class="fa fa-pencil-alt mr-1"></i> {{ __('backend.investors_show.edit_investor') }}</a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-8 mb-4">
                <div class="info-card">
                    <div class="section-header">
                        <div class="title-wrap">
                            <i class="fa fa-id-card"></i>
                            <span>{{ __('backend.investors_show.investor_overview') }}</span>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="info-grid">
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.full_name') }}</div><div class="info-value">{{ $investor->user?->name ?? __('backend.investors_show.empty') }}</div></div>
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.email_address') }}</div><div class="info-value">{{ $investor->user?->email ?? __('backend.investors_show.empty') }}</div></div>
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.status') }}</div><div class="info-value"><span class="badge-soft {{ $statusClass }}">{{ ucfirst($investor->user?->status ?? __('backend.investors_show.empty')) }}</span></div></div>
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.phone') }}</div><div class="info-value">{{ $investor->phone ?? __('backend.investors_show.empty') }}</div></div>
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.company') }}</div><div class="info-value">{{ $investor->company ?? __('backend.investors_show.empty') }}</div></div>
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.position') }}</div><div class="info-value">{{ $investor->position ?? __('backend.investors_show.empty') }}</div></div>
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.investment_type') }}</div><div class="info-value">{{ $investor->investment_type ?? __('backend.investors_show.empty') }}</div></div>
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.budget') }}</div><div class="info-value">{{ $investor->budget !== null ? '$' . number_format($investor->budget, 2) : __('backend.investors_show.empty') }}</div></div>
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.source') }}</div><div class="info-value">{{ $investor->source ?? __('backend.investors_show.empty') }}</div></div>
                            <div class="info-item"><div class="info-label">{{ __('backend.investors_show.registered_on') }}</div><div class="info-value">{{ optional($investor->created_at)->format('Y-m-d h:i A') ?? __('backend.investors_show.empty') }}</div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="section-card">
                    <div class="section-header">
                        <div class="title-wrap">
                            <i class="fa fa-chart-line"></i>
                            <span>{{ __('backend.investors_show.quick_summary') }}</span>
                        </div>
                    </div>

                    <div class="section-body">
                        <div class="mini-summary-card mb-3"><div class="info-label">{{ __('backend.investors_show.notes_count') }}</div><div class="info-value">{{ $investor->investorNotes->count() }}</div></div>
                        <div class="mini-summary-card mb-3"><div class="info-label">{{ __('backend.investors_show.files_count') }}</div><div class="info-value">{{ $investor->files->count() }}</div></div>
                        <div class="mini-summary-card mb-3"><div class="info-label">{{ __('backend.investors_show.activities_count') }}</div><div class="info-value">{{ $investor->activities->count() }}</div></div>
                        <div class="mini-summary-card"><div class="info-label">{{ __('backend.investors_show.funding_records') }}</div><div class="info-value">{{ $projectInvestments->count() }}</div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3"><div class="info-item"><div class="info-label">{{ __('backend.investors_show.interested') }}</div><div class="info-value">{{ $fundingStats['interested'] }}</div></div></div>
            <div class="col-xl-3 col-md-6 mb-3"><div class="info-item"><div class="info-label">{{ __('backend.investors_show.requested') }}</div><div class="info-value">{{ $fundingStats['requested'] }}</div></div></div>
            <div class="col-xl-3 col-md-6 mb-3"><div class="info-item"><div class="info-label">{{ __('backend.investors_show.approved') }}</div><div class="info-value">{{ $fundingStats['approved'] }}</div></div></div>
            <div class="col-xl-3 col-md-6 mb-3"><div class="info-item"><div class="info-label">{{ __('backend.investors_show.rejected') }}</div><div class="info-value">{{ $fundingStats['rejected'] }}</div></div></div>
        </div>

      @include('investors._investments-section')

        <div class="row">
            <div class="col-xl-4 mb-4">
                <div class="section-card h-100">
                    <div class="section-header">
                        <div class="title-wrap">
                            <i class="fa fa-sticky-note"></i>
                            <span>{{ __('backend.investors_show.notes') }}</span>
                        </div>
                    </div>

                    <div class="section-body">
                        <form action="{{ route('admin.investors.notes.store', $investor->user_id) }}"
                              method="POST"
                              class="mb-4 ajax-ui-form"
                              data-loading-text="{{ __('backend.investors_show.saving') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;">{{ __('backend.investors_show.add_note') }}</label>
                                <textarea name="note" rows="4" class="form-control auto-resize" placeholder="{{ __('backend.investors_show.write_note_placeholder') }}"></textarea>
                            </div>

                            <button type="submit" class="btn-primary-custom">
                                <i class="fa fa-plus mr-1"></i> {{ __('backend.investors_show.add_note') }}
                            </button>
                        </form>

                        @if($investor->investorNotes->count() > 0)
                            <ul class="list-clean">
                                @foreach($investor->investorNotes as $note)
                                    <li>
                                        <div class="record-title">{{ $note->user?->name ?? __('backend.investors_show.unknown_user') }}</div>
                                        <div class="record-meta">{{ $note->created_at?->format('Y-m-d h:i A') }}</div>
                                        <div class="record-text">{{ $note->note }}</div>

                                        <form action="{{ route('admin.investors.notes.delete', ['investor' => $investor->user_id, 'note' => $note->id]) }}"
                                              method="POST"
                                              style="margin-top:8px;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger btn-outline-danger-custom"
                                                    onclick="return confirm('{{ __('backend.investors_show.confirm_delete_note') }}')">
                                                {{ __('backend.investors_show.delete') }}
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="empty-state">
                                <i class="fa fa-sticky-note"></i>
                                <div>{{ __('backend.investors_show.no_notes_available') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="section-card h-100">
                    <div class="section-header">
                        <div class="title-wrap">
                            <i class="fa fa-folder-open"></i>
                            <span>{{ __('backend.investors_show.files') }}</span>
                        </div>
                    </div>

                    <div class="section-body">
                        <form action="{{ route('admin.investors.files.upload', $investor->user_id) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="mb-4 ajax-ui-form"
                              data-loading-text="{{ __('backend.investors_show.uploading') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;">{{ __('backend.investors_show.upload_file') }}</label>
                                <input type="file" name="file" class="form-control">
                            </div>

                            <button type="submit" class="btn-primary-custom">
                                <i class="fa fa-upload mr-1"></i> {{ __('backend.investors_show.upload_file') }}
                            </button>
                        </form>

                        @if($investor->files->count() > 0)
                            <ul class="list-clean">
                                @foreach($investor->files as $file)
                                    <li>
                                        <div class="record-title">
                                            <a href="{{ asset('storage/' . $file->path) }}" target="_blank" class="name-link">
                                                {{ $file->filename }}
                                            </a>
                                        </div>

                                        <div class="record-meta">{{ __('backend.investors_show.uploaded') }}: {{ $file->created_at?->format('Y-m-d h:i A') }}</div>
                                        <div class="record-meta">{{ __('backend.investors_show.size') }}: {{ isset($file->size) ? number_format($file->size / 1024, 2) . ' KB' : __('backend.investors_show.empty') }}</div>

                                        <form action="{{ route('admin.investors.files.delete', ['investor' => $investor->user_id, 'file' => $file->id]) }}"
                                              method="POST"
                                              style="margin-top:8px;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger btn-outline-danger-custom"
                                                    onclick="return confirm('{{ __('backend.investors_show.confirm_delete_file') }}')">
                                                {{ __('backend.investors_show.delete') }}
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="empty-state">
                                <i class="fa fa-folder-open"></i>
                                <div>{{ __('backend.investors_show.no_files_uploaded') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="section-card h-100">
                    <div class="section-header">
                        <div class="title-wrap">
                            <i class="fa fa-history"></i>
                            <span>{{ __('backend.investors_show.activities') }}</span>
                        </div>
                    </div>

                    <div class="section-body">
                        @if($investor->activities->count() > 0)
                            <ul class="list-clean">
                                @foreach($investor->activities as $activity)
                                    <li>
                                        <div class="record-title">{{ $activity->user?->name ?? __('backend.investors_show.system') }}</div>
                                        <div class="record-meta">{{ $activity->created_at?->format('Y-m-d h:i A') }}</div>
                                        <div class="record-text">
                                            {{ ucfirst(str_replace('_', ' ', $activity->action)) }}

                                            @if($activity->meta)
                                                <div class="record-meta mt-1">
                                                    {{ is_array($activity->meta) ? json_encode($activity->meta, JSON_UNESCAPED_UNICODE) : $activity->meta }}
                                                </div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="empty-state">
                                <i class="fa fa-history"></i>
                                <div>{{ __('backend.investors_show.no_activities_recorded') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.auto-resize').forEach(el => {
        const resize = () => {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        };
        el.addEventListener('input', resize);
        resize();
    });

    document.querySelectorAll('.ajax-ui-form').forEach(form => {
        form.addEventListener('submit', function () {
            const btn = form.querySelector('button[type="submit"]');
            if (!btn) return;
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> ' + (form.dataset.loadingText || '{{ __('backend.investors_show.processing') }}');
        });
    });

    document.querySelectorAll('.info-card, .section-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(10px)';
        setTimeout(() => {
            card.style.transition = 'all .35s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });
});
</script>
@endsection