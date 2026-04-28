@extends('layouts.auth')

@section('title', __('backend.auth_reset_password.page_title'))
@section('body_class', 'login-page')

@section('auth_actions')
    <a href="{{ route('admin.login.show') }}" class="auth-link-btn">
        {{ __('backend.auth_reset_password.back_to_login') }}
    </a>
@endsection

@section('content')
<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-box bg-white box-shadow border-radius-10 p-4">
                    <div class="login-title text-center">
                        <h2 class="text-primary">{{ __('backend.auth_reset_password.heading') }}</h2>
                        <p>{{ __('backend.auth_reset_password.subtitle') }}</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="input-group custom mt-3">
                            <input type="email" name="email" class="form-control form-control-lg" placeholder="{{ __('backend.auth_reset_password.email') }}" required value="{{ old('email') }}">
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                            </div>
                        </div>

                        <div class="input-group custom mt-3">
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="{{ __('backend.auth_reset_password.new_password') }}" required>
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                            </div>
                        </div>

                        <div class="input-group custom mt-3">
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="{{ __('backend.auth_reset_password.confirm_password') }}" required>
                            <div class="input-group-append custom">
                                <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('backend.auth_reset_password.submit') }}</button>
                            </div>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="{{ route('admin.login.show') }}">{{ __('backend.auth_reset_password.back_to_login') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection