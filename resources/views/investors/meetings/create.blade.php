@extends('layouts.app')

@section('title', __('backend.investor_meetings_create.page_title'))

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="card-box p-4">
        <h4 class="mb-4">{{ __('backend.investor_meetings_create.heading') }} {{ $investor->user?->name }}</h4>

        <form action="{{ route('admin.investors.meetings.store', $investor->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_create.title') }}</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_create.type') }}</label>
                <select name="type" class="form-select">
                    <option value="online">{{ __('backend.investor_meetings_create.types.online') }}</option>
                    <option value="in_person">{{ __('backend.investor_meetings_create.types.in_person') }}</option>
                    <option value="call">{{ __('backend.investor_meetings_create.types.call') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_create.status') }}</label>
                <select name="status" class="form-select">
                    <option value="scheduled">{{ __('backend.investor_meetings_create.statuses.scheduled') }}</option>
                    <option value="completed">{{ __('backend.investor_meetings_create.statuses.completed') }}</option>
                    <option value="cancelled">{{ __('backend.investor_meetings_create.statuses.cancelled') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_create.meeting_date_time') }}</label>
                <input type="datetime-local" name="meeting_at" class="form-control" value="{{ old('meeting_at') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_create.meeting_link') }}</label>
                <input type="text" name="meeting_link" class="form-control" value="{{ old('meeting_link') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_create.location') }}</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}">
            </div>

            <div class="mb-4">
                <label class="form-label">{{ __('backend.investor_meetings_create.notes') }}</label>
                <textarea name="notes" class="form-control" rows="5">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('backend.investor_meetings_create.save_meeting') }}</button>
        </form>
    </div>
</div>
@endsection