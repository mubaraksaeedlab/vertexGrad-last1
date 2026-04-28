@extends('supervisor.layout.app_super')

@section('title', __('backend.supervisor_contact_messages_show.page_title'))

@section('content')
<style>
    .contact-message-show-page {
        padding-bottom: 28px;
    }

    .contact-message-show-page .hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #3b82f6 100%);
        border-radius: 24px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .contact-message-show-page .hero-card::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -40px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }

    .contact-message-show-page .hero-content {
        position: relative;
        z-index: 2;
    }

    .contact-message-show-page .hero-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #fff;
    }

    .contact-message-show-page .hero-text {
        font-size: 14px;
        opacity: .92;
        margin-bottom: 0;
        max-width: 820px;
        line-height: 1.8;
    }

    .contact-message-show-page .section-card {
        background: #fff;
        border-radius: 22px;
        border: 1px solid #eef2f7;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
        overflow: hidden;
    }

    .contact-message-show-page .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .contact-message-show-page .section-header h4,
    .contact-message-show-page .section-header h5 {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
    }

    .contact-message-show-page .section-body {
        padding: 24px;
    }

    .contact-message-show-page .info-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .contact-message-show-page .info-value {
        color: #0f172a;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.6;
    }

    .contact-message-show-page .message-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px;
        line-height: 1.9;
        white-space: pre-line;
    }

    .contact-message-show-page .reply-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 14px;
    }

    .contact-message-show-page .reply-box:last-child {
        margin-bottom: 0;
    }

    .contact-message-show-page .reply-meta {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 8px;
        font-weight: 600;
        line-height: 1.6;
    }

    .contact-message-show-page .reply-text {
        font-size: 14px;
        color: #0f172a;
        line-height: 1.8;
        white-space: pre-line;
    }

    .contact-message-show-page .badge-soft {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .contact-message-show-page .badge-status-new { background: #eff6ff; color: #1d4ed8; }
    .contact-message-show-page .badge-status-progress { background: #fff7ed; color: #c2410c; }
    .contact-message-show-page .badge-status-replied { background: #ecfdf5; color: #15803d; }
    .contact-message-show-page .badge-status-closed { background: #f1f5f9; color: #475569; }

    .contact-message-show-page .badge-type-guest { background: #f8fafc; color: #475569; }
    .contact-message-show-page .badge-type-student { background: #eef2ff; color: #4338ca; }
    .contact-message-show-page .badge-type-investor { background: #ecfeff; color: #0f766e; }

    .contact-message-show-page .form-control,
    .contact-message-show-page .form-select {
        border-radius: 14px !important;
        min-height: 46px;
        border: 1px solid #dbe4ee !important;
        box-shadow: none !important;
    }

    .contact-message-show-page textarea.form-control {
        min-height: 180px;
    }

    .contact-message-show-page .page-main-row {
        margin-bottom: 24px;
    }

    .contact-message-show-page .right-stack > .section-card + .section-card {
        margin-top: 24px;
    }

    .contact-message-show-page .template-helper {
        font-size: 12px;
        color: #64748b;
        margin-top: 8px;
        line-height: 1.6;
    }

    .contact-message-show-page .template-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
</style>

@php
    $statusClass = match($contactMessage->status) {
        'new' => 'badge-status-new',
        'in_progress' => 'badge-status-progress',
        'replied' => 'badge-status-replied',
        'closed' => 'badge-status-closed',
        default => 'badge-status-closed',
    };

    $senderTypeClass = match($contactMessage->sender_type) {
        'student' => 'badge-type-student',
        'investor' => 'badge-type-investor',
        default => 'badge-type-guest',
    };

    $canReply = auth('admin')->user()?->hasPermission('reply_contact_messages');
    $canUpdateStatus = auth('admin')->user()?->hasPermission('update_contact_message_status');

    $defaultTemplateKey = match($contactMessage->subject) {
        'academic' => 'academic_ack',
        'investor' => 'investor_ack',
        'support' => 'support_ack',
        default => 'general_ack',
    };
@endphp

<div class="pd-ltr-20 xs-pd-20-10 contact-message-show-page">
    <div class="min-height-200px">

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 14px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 14px;">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="hero-card">
            <div class="hero-content d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
                <div>
                    <div class="hero-title">{{ __('backend.supervisor_contact_messages_show.heading') }} #{{ $contactMessage->id }}</div>
                    <p class="hero-text">
                        {{ __('backend.supervisor_contact_messages_show.subtitle') }}
                    </p>
                </div>

                <a href="{{ route('supervisor.contact-messages.index') }}"
                   class="btn btn-light btn-sm"
                   style="border-radius: 10px; font-weight: 700;">
                    {{ __('backend.supervisor_contact_messages_show.back') }}
                </a>
            </div>
        </div>

        <div class="section-card mb-4">
            <div class="section-header">
                <h5>{{ __('backend.supervisor_contact_messages_show.message_details') }}</h5>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="info-label">{{ __('backend.supervisor_contact_messages_show.sender_name') }}</div>
                        <div class="info-value">{{ $contactMessage->name }}</div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="info-label">{{ __('backend.supervisor_contact_messages_show.email_address') }}</div>
                        <div class="info-value">{{ $contactMessage->email }}</div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="info-label">{{ __('backend.supervisor_contact_messages_show.subject') }}</div>
                        <div class="info-value">{{ $contactMessage->subject_label }}</div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="info-label">{{ __('backend.supervisor_contact_messages_show.sender_type') }}</div>
                        <div class="info-value">
                            <span class="badge-soft {{ $senderTypeClass }}">
                                {{ $contactMessage->sender_type_label }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="info-label">{{ __('backend.supervisor_contact_messages_show.current_status') }}</div>
                        <div class="info-value">
                            <span class="badge-soft {{ $statusClass }}">
                                {{ $contactMessage->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="info-label">{{ __('backend.supervisor_contact_messages_show.submitted_at') }}</div>
                        <div class="info-value">{{ $contactMessage->created_at?->format('Y-m-d h:i A') }}</div>
                    </div>

                    <div class="col-12">
                        <div class="info-label">{{ __('backend.supervisor_contact_messages_show.message_content') }}</div>
                        <div class="message-box">{{ $contactMessage->message }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row page-main-row align-items-start">
            <div class="col-xl-8 mb-4">
                <div class="section-card">
                    <div class="section-header">
                        <h5>{{ __('backend.supervisor_contact_messages_show.reply_history') }}</h5>
                    </div>

                    <div class="section-body">
                        @forelse($contactMessage->replies as $reply)
                            <div class="reply-box">
                                <div class="reply-meta">
                                    {{ __('backend.supervisor_contact_messages_show.replied_by') }} {{ $reply->admin?->name ?? __('backend.supervisor_contact_messages_show.system_user') }}
                                    • {{ $reply->sent_at?->format('Y-m-d h:i A') ?? $reply->created_at?->format('Y-m-d h:i A') }}
                                    • {{ __('backend.supervisor_contact_messages_show.channel') }}: {{ ucfirst($reply->channel) }}
                                    • {{ $reply->is_sent ? __('backend.supervisor_contact_messages_show.sent') : __('backend.supervisor_contact_messages_show.draft_not_sent') }}
                                </div>
                                <div class="reply-text">{{ $reply->reply_message }}</div>
                            </div>
                        @empty
                            <div class="text-muted">{{ __('backend.supervisor_contact_messages_show.no_replies_yet') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="right-stack">
                    @if($canUpdateStatus)
                        <div class="section-card">
                            <div class="section-header">
                                <h5>{{ __('backend.supervisor_contact_messages_show.update_status') }}</h5>
                            </div>

                            <div class="section-body">
                                <form action="{{ route('supervisor.contact-messages.update-status', $contactMessage) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <div class="mb-3">
                                        <label for="status" class="info-label">{{ __('backend.supervisor_contact_messages_show.select_status') }}</label>
                                        <select id="status" name="status" class="form-select" required>
                                            <option value="new" {{ $contactMessage->status === 'new' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_show.status_new') }}</option>
                                            <option value="in_progress" {{ $contactMessage->status === 'in_progress' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_show.status_in_progress') }}</option>
                                            <option value="replied" {{ $contactMessage->status === 'replied' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_show.status_replied') }}</option>
                                            <option value="closed" {{ $contactMessage->status === 'closed' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_show.status_closed') }}</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100" style="border-radius: 12px; font-weight: 700;">
                                        {{ __('backend.supervisor_contact_messages_show.save_status') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if($canReply)
                        <div class="section-card">
                            <div class="section-header">
                                <h5>{{ __('backend.supervisor_contact_messages_show.send_reply') }}</h5>
                            </div>

                            <div class="section-body">
                                <form action="{{ route('supervisor.contact-messages.reply', $contactMessage) }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="reply_template" class="info-label">{{ __('backend.supervisor_contact_messages_show.quick_reply_template') }}</label>
                                        <select id="reply_template" class="form-select">
                                            <option value="">{{ __('backend.supervisor_contact_messages_show.select_template') }}</option>
                                            <option value="academic_ack" {{ $defaultTemplateKey === 'academic_ack' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_show.templates.academic_ack') }}</option>
                                            <option value="investor_ack" {{ $defaultTemplateKey === 'investor_ack' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_show.templates.investor_ack') }}</option>
                                            <option value="support_ack" {{ $defaultTemplateKey === 'support_ack' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_show.templates.support_ack') }}</option>
                                            <option value="general_ack" {{ $defaultTemplateKey === 'general_ack' ? 'selected' : '' }}>{{ __('backend.supervisor_contact_messages_show.templates.general_ack') }}</option>
                                        </select>
                                        <div class="template-helper">
                                            {{ __('backend.supervisor_contact_messages_show.template_helper') }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="template-actions">
                                            <button type="button" id="apply_template_btn" class="btn btn-outline-primary btn-sm" style="border-radius: 10px; font-weight: 700;">
                                                {{ __('backend.supervisor_contact_messages_show.apply_template') }}
                                            </button>

                                            <button type="button" id="clear_template_btn" class="btn btn-outline-secondary btn-sm" style="border-radius: 10px; font-weight: 700;">
                                                {{ __('backend.supervisor_contact_messages_show.clear') }}
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="reply_message" class="info-label">{{ __('backend.supervisor_contact_messages_show.reply_message') }}</label>
                                        <textarea
                                            id="reply_message"
                                            name="reply_message"
                                            class="form-control"
                                            required
                                            placeholder="{{ __('backend.supervisor_contact_messages_show.reply_placeholder') }}"
                                        >{{ old('reply_message') }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100" style="border-radius: 12px; font-weight: 700;">
                                        {{ __('backend.supervisor_contact_messages_show.send_reply_by_email') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const templateSelect = document.getElementById('reply_template');
    const applyBtn = document.getElementById('apply_template_btn');
    const clearBtn = document.getElementById('clear_template_btn');
    const replyTextarea = document.getElementById('reply_message');

    if (!templateSelect || !applyBtn || !clearBtn || !replyTextarea) return;

    const templates = {
        academic_ack: `{{ __('backend.supervisor_contact_messages_show.template_bodies.academic_ack.greeting', ['name' => $contactMessage->name]) }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.academic_ack.body_1') }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.academic_ack.body_2') }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.signature') }}`,

        investor_ack: `{{ __('backend.supervisor_contact_messages_show.template_bodies.investor_ack.greeting', ['name' => $contactMessage->name]) }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.investor_ack.body_1') }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.investor_ack.body_2') }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.signature') }}`,

        support_ack: `{{ __('backend.supervisor_contact_messages_show.template_bodies.support_ack.greeting', ['name' => $contactMessage->name]) }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.support_ack.body_1') }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.support_ack.body_2') }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.signature') }}`,

        general_ack: `{{ __('backend.supervisor_contact_messages_show.template_bodies.general_ack.greeting', ['name' => $contactMessage->name]) }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.general_ack.body_1') }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.general_ack.body_2') }}

{{ __('backend.supervisor_contact_messages_show.template_bodies.signature') }}`
    };

    function applySelectedTemplate() {
        const selected = templateSelect.value;

        if (selected && templates[selected]) {
            replyTextarea.value = templates[selected];
            replyTextarea.focus();
        }
    }

    applyBtn.addEventListener('click', function () {
        applySelectedTemplate();
    });

    templateSelect.addEventListener('change', function () {
        applySelectedTemplate();
    });

    clearBtn.addEventListener('click', function () {
        replyTextarea.value = '';
        replyTextarea.focus();
    });

    if (!replyTextarea.value.trim() && templateSelect.value) {
        applySelectedTemplate();
    }
});
</script>
@endpush