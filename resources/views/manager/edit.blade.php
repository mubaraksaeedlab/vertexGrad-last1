@extends('layouts.app')

@section('title', __('backend.managers_edit.page_title'))

@section('content')
<div class="container">
    <h1>{{ __('backend.managers_edit.heading') }}</h1>

    <form action="{{ route('manager.update', $manager) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>{{ __('backend.managers_edit.user_label') }}</label>
            <input type="text" class="form-control" value="{{ $manager->user->name }} ({{ $manager->user->email }})" disabled>
        </div>

        <div class="mb-3">
            <label>{{ __('backend.managers_edit.department_label') }}</label>
            <input type="text" name="department" class="form-control" value="{{ $manager->department }}">
        </div>

        <button type="submit" class="btn btn-success">{{ __('backend.managers_edit.update') }}</button>
        <a href="{{ route('manager.index') }}" class="btn btn-secondary">{{ __('backend.managers_edit.cancel') }}</a>
    </form>
</div>
@endsection