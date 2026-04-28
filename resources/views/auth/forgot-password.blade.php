@extends('layouts.auth')

@section('title', __('backend.auth_forgot_password.page_title'))
@section('body_class', 'bg-light')

@section('auth_actions')
    <a href="{{ route('admin.login.show') }}" class="auth-link-btn">
        {{ __('backend.auth_forgot_password.back_to_login') }}
    </a>
@endsection

@push('auth_styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 420px;">
        <h3 class="card-title text-center mb-3">{{ __('backend.auth_forgot_password.heading') }}</h3>
        <p class="text-center text-muted mb-4">{{ __('backend.auth_forgot_password.subtitle') }}</p>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control form-control-lg" placeholder="{{ __('backend.auth_forgot_password.your_email') }}" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">{{ __('backend.auth_forgot_password.send_reset_link') }}</button>
            </div>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('admin.login.show') }}">{{ __('backend.auth_forgot_password.back_to_login') }}</a>
        </div>
    </div>
</div>
@endsection

@push('auth_scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endpush