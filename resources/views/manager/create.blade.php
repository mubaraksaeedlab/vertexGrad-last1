@extends('layouts.app')

@section('title', __('backend.managers_create.page_title'))

@section('content')
<div class="container">
    <h1>{{ __('backend.managers_create.heading') }}</h1>

    <form action="{{ route('manager.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>{{ __('backend.managers_create.user_label') }}</label>
            <select name="user_id" class="form-control" required>
                <option value="">{{ __('backend.managers_create.select_user') }}</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>{{ __('backend.managers_create.department_label') }}</label>
            <input type="text" name="department" class="form-control" placeholder="{{ __('backend.managers_create.department_placeholder') }}">
        </div>

        <button type="submit" class="btn btn-success">{{ __('backend.managers_create.save') }}</button>
        <a href="{{ route('manager.index') }}" class="btn btn-secondary">{{ __('backend.managers_create.cancel') }}</a>
    </form>
</div>
@endsection