@extends('layouts.app')

@section('title', __('backend.investor_meetings_edit.page_title'))

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="card-box p-4">
        <h4 class="mb-4">{{ __('backend.investor_meetings_edit.heading') }} {{ $investor->user?->name }}</h4>

        <form action="{{ route('admin.investors.meetings.update', [$investor->id, $meeting->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_edit.title') }}</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $meeting->title) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_edit.type') }}</label>
                <select name="type" class="form-select">
                    <option value="online" {{ old('type', $meeting->type) === 'online' ? 'selected' : '' }}>{{ __('backend.investor_meetings_edit.types.online') }}</option>
                    <option value="in_person" {{ old('type', $meeting->type) === 'in_person' ? 'selected' : '' }}>{{ __('backend.investor_meetings_edit.types.in_person') }}</option>
                    <option value="call" {{ old('type', $meeting->type) === 'call' ? 'selected' : '' }}>{{ __('backend.investor_meetings_edit.types.call') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_edit.status') }}</label>
                <select name="status" class="form-select">
                    <option value="scheduled" {{ old('status', $meeting->status) === 'scheduled' ? 'selected' : '' }}>{{ __('backend.investor_meetings_edit.statuses.scheduled') }}</option>
                    <option value="completed" {{ old('status', $meeting->status) === 'completed' ? 'selected' : '' }}>{{ __('backend.investor_meetings_edit.statuses.completed') }}</option>
                    <option value="cancelled" {{ old('status', $meeting->status) === 'cancelled' ? 'selected' : '' }}>{{ __('backend.investor_meetings_edit.statuses.cancelled') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_edit.meeting_date_time') }}</label>
                <input type="datetime-local"
                       name="meeting_at"
                       class="form-control"
                       value="{{ old('meeting_at', optional($meeting->meeting_at)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_edit.meeting_link') }}</label>
                <input type="text" name="meeting_link" class="form-control" value="{{ old('meeting_link', $meeting->meeting_link) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_meetings_edit.location') }}</label>
                <input type="text" name="location" class="form-control" value="{{ old('location', $meeting->location) }}">
            </div>

            <div class="mb-4">
                <label class="form-label">{{ __('backend.investor_meetings_edit.notes') }}</label>
                <textarea name="notes" class="form-control" rows="5">{{ old('notes', $meeting->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('backend.investor_meetings_edit.update_meeting') }}</button>
        </form>
    </div>
</div>
@endsection