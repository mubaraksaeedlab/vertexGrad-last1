@extends('supervisor.layout.app_super')

@section('title', __('backend.supervisor_contact_messages_index.page_title'))

@section('content')
<style>
    .contact-messages-page .hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #3b82f6 100%);
        border-radius: 24px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .contact-messages-page .hero-card::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -40px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .contact-messages-page .hero-content {
        position: relative;
        z-index: 2;
    }

    .contact-messages-page .hero-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #fff;
    }

    .contact-messages-page .hero-text {
        font-size: 14px;
        opacity: .92;
        margin-bottom: 0;
        max-width: 820px;
        line-height: 1.8;
    }

    .contact-messages-page .section-card {
        background: #fff;
        border-radius: 22px;
        border: 1px solid #eef2f7;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .contact-messages-page .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .contact-messages-page .section-header h4,
    .contact-messages-page .section-header h5 {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
    }

    .contact-messages-page .section-subtext {
        margin-top: 6px;
        color: #64748b;
        font-size: 13px;
    }

    .contact-messages-page .section-body {
        padding: 24px;
    }

    .contact-messages-page .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
    }

    .contact-messages-page .form-control,
    .contact-messages-page .form-select {
        border-radius: 14px !important;
        min-height: 46px;
        border: 1px solid #dbe4ee !important;
        box-shadow: none !important;
    }

    .contact-messages-page .stats-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2ff;
        height: 100%;
    }

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

    .contact-messages-page .table th {
        white-space: nowrap;
    }

    .contact-messages-page .sender-name {
        font-weight: 700;
        color: #1e293b;
        display: block;
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
    }

    .contact-messages-page .badge-status-new { background: #eff6ff; color: #1d4ed8; }
    .contact-messages-page .badge-status-progress { background: #fff7ed; color: #c2410c; }
    .contact-messages-page .badge-status-replied { background: #ecfdf5; color: #15803d; }
    .contact-messages-page .badge-status-closed { background: #f1f5f9; color: #475569; }

    .contact-messages-page .badge-type-guest { background: #f8fafc; color: #475569; }
    .contact-messages-page .badge-type-student { background: #eef2ff; color: #4338ca; }
    .contact-messages-page .badge-type-investor { background: #ecfeff; color: #0f766e; }
</style>

@php
    $totalMessages = $messages->total();
    $newCount = \App\Models\ContactMessage::where('status', 'new')->count();
    $inProgressCount = \App\Models\ContactMessage::where('status', 'in_progress')->count();
    $closedCount = \App\Models\ContactMessage::where('status', 'closed')->count();
@endphp

<div class="pd-ltr-20 xs-pd-20-10 contact-messages-page">
    <div class="min-height-200px">

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="hero-card">
            <div class="hero-content d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
                <div>
                    <div class="hero-title">{{ __('backend.supervisor_contact_messages_index.heading') }}</div>
                    <p class="hero-text">
                        {{ __('backend.supervisor_contact_messages_index.subtitle') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-4 col-md-4 col-12 mb-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $totalMessages }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_contact_messages_index.total_messages') }}</p>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 col-12 mb-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $newCount }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_contact_messages_index.new') }}</p>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 col-12 mb-3">
                <div class="stats-card">
                    <div class="stats-number">{{ $inProgressCount }}</div>
                    <p class="stats-label">{{ __('backend.supervisor_contact_messages_index.in_progress') }}</p>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h5>{{ __('backend.supervisor_contact_messages_index.filters') }}</h5>
                <div class="section-subtext">
                    {{ __('backend.supervisor_contact_messages_index.filters_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                <form method="GET" action="{{ route('supervisor.contact-messages.index') }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.supervisor_contact_messages_index.search') }}</label>
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="{{ __('backend.supervisor_contact_messages_index.search_placeholder') }}">
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.supervisor_contact_messages_index.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="">{{ __('backend.supervisor_contact_messages_index.all_statuses') }}</option>
                                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.new') }}</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.in_progress') }}</option>
                                <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.replied') }}</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.closed') }}</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.supervisor_contact_messages_index.subject') }}</label>
                            <select name="subject" class="form-select">
                                <option value="">{{ __('backend.supervisor_contact_messages_index.all_subjects') }}</option>
                                <option value="academic" {{ request('subject') === 'academic' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.subjects.academic') }}</option>
                                <option value="investor" {{ request('subject') === 'investor' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.subjects.investor') }}</option>
                                <option value="support" {{ request('subject') === 'support' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.subjects.support') }}</option>
                                <option value="other" {{ request('subject') === 'other' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.subjects.other') }}</option>
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.supervisor_contact_messages_index.sender_type') }}</label>
                            <select name="sender_type" class="form-select">
                                <option value="">{{ __('backend.supervisor_contact_messages_index.all_sender_types') }}</option>
                                <option value="guest" {{ request('sender_type') === 'guest' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.sender_types.guest') }}</option>
                                <option value="student" {{ request('sender_type') === 'student' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.sender_types.student') }}</option>
                                <option value="investor" {{ request('sender_type') === 'investor' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_index.sender_types.investor') }}</option>
                            </select>
                        </div>

                        <div class="col-12 d-flex flex-wrap" style="gap: 10px;">
                            <button type="submit" class="btn btn-primary" style="border-radius: 12px; font-weight: 700;">
                                {{ __('backend.supervisor_contact_messages_index.filter') }}
                            </button>

                            <a href="{{ route('supervisor.contact-messages.index') }}"
                               class="btn btn-light"
                               style="border-radius: 12px; font-weight: 700; border: 1px solid #dbe4f0;">
                                {{ __('backend.supervisor_contact_messages_index.reset') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h5>{{ __('backend.supervisor_contact_messages_index.submitted_messages') }}</h5>
                <div class="section-subtext">
                    {{ __('backend.supervisor_contact_messages_index.submitted_messages_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                <div class="table-responsive">
                    <table class="table table-striped hover nowrap mb-0">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th>#</th>
                                <th>{{ __('backend.supervisor_contact_messages_index.sender') }}</th>
                                <th>{{ __('backend.supervisor_contact_messages_index.subject') }}</th>
                                <th>{{ __('backend.supervisor_contact_messages_index.sender_type') }}</th>
                                <th>{{ __('backend.supervisor_contact_messages_index.status') }}</th>
                                <th>{{ __('backend.supervisor_contact_messages_index.submitted') }}</th>
                                <th width="120">{{ __('backend.supervisor_contact_messages_index.action') }}</th>
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
                                        {{ $message->subject_label }}
                                        <div class="mini-text">{{ \Illuminate\Support\Str::limit($message->message, 55) }}</div>
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
                                        {{ $message->created_at?->format('Y-m-d') }}
                                        <div class="mini-text">{{ $message->created_at?->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <a href="{{ route('supervisor.contact-messages.show', $message) }}"
                                           class="btn btn-primary btn-sm"
                                           style="border-radius: 10px; font-weight: 600;">
                                            {{ __('backend.supervisor_contact_messages_index.view') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">{{ __('backend.supervisor_contact_messages_index.no_contact_messages_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $messages->links() }}
                </div>
            </div>
        </div>

    </div>
</div>
@endsection