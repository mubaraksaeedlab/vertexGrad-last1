@extends('layouts.app')

@section('title', __('backend.contact_messages_index.page_title'))

@section('content')
<style>
    .contact-messages-page .page-header-card {
        background: linear-gradient(135deg, #0d1b4c 0%, #1b00ff 100%);
        border-radius: 20px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 12px 30px rgba(27, 0, 255, 0.18);
    }

    .contact-messages-page .page-header-card h3 {
        margin: 0;
        font-weight: 700;
        color: #fff;
    }

    .contact-messages-page .page-header-card p {
        margin: 8px 0 0;
        opacity: 0.9;
    }

    .contact-messages-page .stats-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2ff;
        height: 100%;
        transition: 0.3s ease;
    }

    .contact-messages-page .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.10);
    }

    .contact-messages-page .stats-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 14px;
        color: #fff;
    }

    .contact-messages-page .stats-icon.primary { background: linear-gradient(135deg, #1b00ff, #4f46e5); }
    .contact-messages-page .stats-icon.success { background: linear-gradient(135deg, #16a34a, #22c55e); }
    .contact-messages-page .stats-icon.warning { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .contact-messages-page .stats-icon.info { background: linear-gradient(135deg, #0891b2, #06b6d4); }

    .contact-messages-page .stats-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 8px;
    }

    .contact-messages-page .stats-label {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0;
    }

    .contact-messages-page .filter-card,
    .contact-messages-page .table-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .contact-messages-page .filter-card-header,
    .contact-messages-page .table-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .contact-messages-page .filter-card-header h5,
    .contact-messages-page .table-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .contact-messages-page .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
    }

    .contact-messages-page .form-control,
    .contact-messages-page .form-select {
        border-radius: 12px;
        min-height: 42px;
        border: 1px solid #dbe4f0;
        box-shadow: none;
    }

    .contact-messages-page .btn-soft {
        border-radius: 12px;
        font-weight: 600;
        padding: 9px 14px;
        text-decoration: none;
        border: 1px solid #dbe4f0;
        background: #fff;
        color: #0f172a;
    }

    .contact-messages-page .btn-soft:hover {
        text-decoration: none;
        color: #0f172a;
        background: #f8fafc;
    }

    .contact-messages-page .modern-table {
        margin-bottom: 0;
        width: 100%;
        table-layout: auto;
    }

    .contact-messages-page .modern-table thead th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-size: 13px;
    }

    .contact-messages-page .modern-table tbody td {
        padding: 14px 10px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 13px;
    }

    .contact-messages-page .modern-table tbody tr:hover {
        background: #fafcff;
    }

    .contact-messages-page .sender-name {
        font-weight: 700;
        color: #1e293b;
        display: block;
        max-width: 220px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .contact-messages-page .mini-text {
        font-size: 11px;
        color: #64748b;
        margin-top: 3px;
        line-height: 1.5;
    }

    .contact-messages-page .badge-soft {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .contact-messages-page .badge-status-new {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .contact-messages-page .badge-status-progress {
        background: #fff7ed;
        color: #c2410c;
    }

    .contact-messages-page .badge-status-replied {
        background: #ecfdf5;
        color: #15803d;
    }

    .contact-messages-page .badge-status-closed {
        background: #f1f5f9;
        color: #475569;
    }

    .contact-messages-page .badge-type-guest {
        background: #f8fafc;
        color: #475569;
    }

    .contact-messages-page .badge-type-student {
        background: #eef2ff;
        color: #4338ca;
    }

    .contact-messages-page .badge-type-investor {
        background: #ecfeff;
        color: #0f766e;
    }

    .contact-messages-page .icon-action {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.10);
        background: linear-gradient(135deg, #1b00ff, #4338ca);
    }

    .contact-messages-page .icon-action:hover {
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .contact-messages-page .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #64748b;
    }

    .contact-messages-page .empty-state i {
        font-size: 42px;
        margin-bottom: 12px;
        color: #cbd5e1;
    }
</style>

@php
    $totalMessages = $messages->total();
    $newCount = \App\Models\ContactMessage::where('status', 'new')->count();
    $inProgressCount = \App\Models\ContactMessage::where('status', 'in_progress')->count();
    $closedCount = \App\Models\ContactMessage::where('status', 'closed')->count();
@endphp

<div class="pd-ltr-20 xs-pd-20-10 contact-messages-page">
    <div class="min-height-200px">
        <div class="page-header-card mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                <div>
                    <h3>{{ __('backend.contact_messages_index.heading') }}</h3>
                    <p>{{ __('backend.contact_messages_index.subtitle') }}</p>
                </div>

                <div class="d-flex flex-wrap" style="gap: 10px;">
                    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-light btn-soft">
                        <i class="fa fa-refresh mr-1"></i> {{ __('backend.contact_messages_index.refresh') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 col-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon primary">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <div class="stats-number">{{ $totalMessages }}</div>
                    <p class="stats-label">{{ __('backend.contact_messages_index.total_messages') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon info">
                        <i class="fa fa-bell"></i>
                    </div>
                    <div class="stats-number">{{ $newCount }}</div>
                    <p class="stats-label">{{ __('backend.contact_messages_index.new') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon warning">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                    <div class="stats-number">{{ $inProgressCount }}</div>
                    <p class="stats-label">{{ __('backend.contact_messages_index.in_progress') }}</p>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon success">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="stats-number">{{ $closedCount }}</div>
                    <p class="stats-label">{{ __('backend.contact_messages_index.closed') }}</p>
                </div>
            </div>
        </div>

        <div class="filter-card mb-4">
            <div class="filter-card-header">
                <div>
                    <h5>{{ __('backend.contact_messages_index.filters') }}</h5>
                    <small class="text-muted">{{ __('backend.contact_messages_index.filters_subtitle') }}</small>
                </div>
            </div>

            <div class="p-4">
                <form method="GET" action="{{ route('admin.contact-messages.index') }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.contact_messages_index.search') }}</label>
                            <input type="text" name="search" class="form-control" placeholder="{{ __('backend.contact_messages_index.search_placeholder') }}" value="{{ request('search') }}">
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.contact_messages_index.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="">{{ __('backend.contact_messages_index.all_statuses') }}</option>
                                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.new') }}</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.in_progress') }}</option>
                                <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.replied') }}</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.closed') }}</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.contact_messages_index.subject') }}</label>
                            <select name="subject" class="form-select">
                                <option value="">{{ __('backend.contact_messages_index.all_subjects') }}</option>
                                <option value="academic" {{ request('subject') === 'academic' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.subjects.academic') }}</option>
                                <option value="investor" {{ request('subject') === 'investor' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.subjects.investor') }}</option>
                                <option value="support" {{ request('subject') === 'support' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.subjects.support') }}</option>
                                <option value="other" {{ request('subject') === 'other' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.subjects.other') }}</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.contact_messages_index.sender_type') }}</label>
                            <select name="sender_type" class="form-select">
                                <option value="">{{ __('backend.contact_messages_index.all_sender_types') }}</option>
                                <option value="guest" {{ request('sender_type') === 'guest' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.sender_types.guest') }}</option>
                                <option value="student" {{ request('sender_type') === 'student' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.sender_types.student') }}</option>
                                <option value="investor" {{ request('sender_type') === 'investor' ? 'selected' : '' }}>{{ __('backend.contact_messages_index.sender_types.investor') }}</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex flex-wrap" style="gap: 10px;">
                            <button type="submit" class="btn btn-primary" style="border-radius: 12px; font-weight: 700;">
                                <i class="fa fa-search mr-1"></i> {{ __('backend.contact_messages_index.filter') }}
                            </button>

                            <a href="{{ route('admin.contact-messages.index') }}"
                               class="btn btn-light"
                               style="border-radius: 12px; font-weight: 700; border: 1px solid #dbe4f0;">
                                {{ __('backend.contact_messages_index.reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <div>
                    <h5>{{ __('backend.contact_messages_index.submitted_messages') }}</h5>
                    <small class="text-muted">{{ __('backend.contact_messages_index.submitted_messages_subtitle') }}</small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('backend.contact_messages_index.sender') }}</th>
                            <th>{{ __('backend.contact_messages_index.subject') }}</th>
                            <th>{{ __('backend.contact_messages_index.sender_type') }}</th>
                            <th>{{ __('backend.contact_messages_index.status') }}</th>
                            <th>{{ __('backend.contact_messages_index.submitted') }}</th>
                            <th class="text-right">{{ __('backend.contact_messages_index.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            @php
                                $statusClass = match($message->status) {
                                    'new' => 'badge-status-new',
                                    'in_progress' => 'badge-status-progress',
                                    'replied' => 'badge-status-replied',
                                    'closed' => 'badge-status-closed',
                                    default => 'badge-status-closed',
                                };

                                $senderTypeClass = match($message->sender_type) {
                                    'student' => 'badge-type-student',
                                    'investor' => 'badge-type-investor',
                                    default => 'badge-type-guest',
                                };
                            @endphp

                            <tr>
                                <td>{{ $message->id }}</td>

                                <td>
                                    <span class="sender-name">{{ $message->name }}</span>
                                    <div class="mini-text">{{ $message->email }}</div>
                                </td>

                                <td>
                                    <div>{{ $message->subject_label }}</div>
                                    <div class="mini-text">
                                        {{ \Illuminate\Support\Str::limit($message->message, 55) }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge-soft {{ $senderTypeClass }}">
                                        {{ $message->sender_type_label }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge-soft {{ $statusClass }}">
                                        {{ $message->status_label }}
                                    </span>
                                </td>

                                <td>
                                    <div>{{ $message->created_at?->format('Y-m-d') }}</div>
                                    <div class="mini-text">{{ $message->created_at?->format('h:i A') }}</div>
                                </td>

                                <td class="text-right">
                                    <a href="{{ route('admin.contact-messages.show', $message) }}" class="icon-action" title="{{ __('backend.contact_messages_index.view') }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fa fa-envelope-open"></i>
                                        <div>{{ __('backend.contact_messages_index.no_contact_messages_found') }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $messages->links() }}
            </div>
        </div>

    </div>
</div>
@endsection