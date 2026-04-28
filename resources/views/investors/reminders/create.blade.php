@extends('layouts.app')

@section('title', __('backend.investors_reminders_create.page_title'))

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="card-box p-4">
        <h4 class="mb-4">{{ __('backend.investors_reminders_create.heading', ['name' => $investor->user?->name]) }}</h4>

        <form action="{{ route('admin.investors.reminders.store', $investor->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_create.title') }}</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_create.message') }}</label>
                <textarea name="message" class="form-control" rows="4">{{ old('message') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_create.type') }}</label>
                <select name="type" class="form-select">
                    <option value="meeting">{{ __('backend.investors_reminders_create.types.meeting') }}</option>
                    <option value="follow_up">{{ __('backend.investors_reminders_create.types.follow_up') }}</option>
                    <option value="contract">{{ __('backend.investors_reminders_create.types.contract') }}</option>
                    <option value="custom">{{ __('backend.investors_reminders_create.types.custom') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_create.status') }}</label>
                <select name="status" class="form-select">
                    <option value="pending">{{ __('backend.investors_reminders_create.statuses.pending') }}</option>
                    <option value="sent">{{ __('backend.investors_reminders_create.statuses.sent') }}</option>
                    <option value="completed">{{ __('backend.investors_reminders_create.statuses.completed') }}</option>
                    <option value="cancelled">{{ __('backend.investors_reminders_create.statuses.cancelled') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investors_reminders_create.remind_at') }}</label>
                <input type="datetime-local" name="remind_at" class="form-control" value="{{ old('remind_at') }}">
            </div>

            <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" name="send_in_app" value="1" checked>
                <label class="form-check-label">{{ __('backend.investors_reminders_create.send_in_app_notification') }}</label>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" name="send_email" value="1">
                <label class="form-check-label">{{ __('backend.investors_reminders_create.send_email') }}</label>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('backend.investors_reminders_create.save_reminder') }}</button>
        </form>
    </div>
</div>
@endsection