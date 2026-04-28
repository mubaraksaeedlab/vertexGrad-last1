@extends('layouts.app')

@section('title', __('backend.investors_reminders_edit.page_title'))

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="card-box p-4">
        <h4 class="mb-4">{{ __('backend.investors_reminders_edit.heading', ['name' => $investor->user?->name]) }}</h4>

        <form action="{{ route('admin.investors.reminders.update', [$investor->id, $reminder->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_edit.title') }}</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $reminder->title) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_edit.message') }}</label>
                <textarea name="message" class="form-control" rows="4">{{ old('message', $reminder->message) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_edit.type') }}</label>
                <select name="type" class="form-select">
                    <option value="meeting" {{ old('type', $reminder->type) === 'meeting' ? 'selected' : '' }}>{{ __('backend.investors_reminders_edit.types.meeting') }}</option>
                    <option value="follow_up" {{ old('type', $reminder->type) === 'follow_up' ? 'selected' : '' }}>{{ __('backend.investors_reminders_edit.types.follow_up') }}</option>
                    <option value="contract" {{ old('type', $reminder->type) === 'contract' ? 'selected' : '' }}>{{ __('backend.investors_reminders_edit.types.contract') }}</option>
                    <option value="custom" {{ old('type', $reminder->type) === 'custom' ? 'selected' : '' }}>{{ __('backend.investors_reminders_edit.types.custom') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_edit.status') }}</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ old('status', $reminder->status) === 'pending' ? 'selected' : '' }}>{{ __('backend.investors_reminders_edit.statuses.pending') }}</option>
                    <option value="sent" {{ old('status', $reminder->status) === 'sent' ? 'selected' : '' }}>{{ __('backend.investors_reminders_edit.statuses.sent') }}</option>
                    <option value="completed" {{ old('status', $reminder->status) === 'completed' ? 'selected' : '' }}>{{ __('backend.investors_reminders_edit.statuses.completed') }}</option>
                    <option value="cancelled" {{ old('status', $reminder->status) === 'cancelled' ? 'selected' : '' }}>{{ __('backend.investors_reminders_edit.statuses.cancelled') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_edit.remind_at') }}</label>
                <input type="datetime-local" name="remind_at" class="form-control" value="{{ old('remind_at', optional($reminder->remind_at)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" name="send_in_app" value="1" {{ $reminder->send_in_app ? 'checked' : '' }}>
                <label class="form-check-label">{{ __('backend.investors_reminders_edit.send_in_app_notification') }}</label>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" name="send_email" value="1" {{ $reminder->send_email ? 'checked' : '' }}>
                <label class="form-check-label">{{ __('backend.investors_reminders_edit.send_email') }}</label>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('backend.investors_reminders_edit.update_reminder') }}</button>
        </form>
    </div>
</div>
@endsection