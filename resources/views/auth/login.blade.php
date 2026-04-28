@extends('layouts.auth')

@section('title', __('backend.auth_login.page_title'))
@section('body_class', 'login-page')

@section('auth_actions')
    <a href="{{ route('admin.register.show') }}" class="auth-link-btn">
        {{ __('backend.auth_login.register') }}
    </a>
@endsection

@push('auth_styles')
<style>
    .text-danger {
        font-size: 14px;
        margin-top: 5px;
        display: block;
    }

    .login-box {
        margin-top: 40px;
    }

    .select-role {
        margin-bottom: 15px;
    }

    .btn-group-toggle .btn {
        border: 1px solid #ddd;
        padding: 10px 20px;
        margin-inline-end: 5px;
        border-radius: 6px;
    }

    .btn.active {
        background-color: #1b00ff;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-7">
                <img src="{{ asset('vendors/images/login-page-img.png') }}" alt="{{ __('backend.auth_login.login_image_alt') }}">
            </div>
            <div class="col-md-6 col-lg-5">
                <div class="login-box bg-white box-shadow border-radius-10 p-4">

                    <div id="login-form">
                        <div class="login-title">
                            <h2 class="text-center text-primary">{{ __('backend.auth_login.heading') }}</h2>
                        </div>

                        <form action="{{ route('admin.login.post') }}" method="POST">
                            @csrf

                            <div class="select-role text-center mb-3">
                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                    <label class="btn {{ old('role') == 'Manager' ? 'active' : '' }}">
                                        <input type="radio" name="role" value="Manager" {{ old('role') == 'Manager' ? 'checked' : '' }}>
                                        <div class="icon">
                                            <img src="{{ asset('vendors/images/briefcase.svg') }}" class="svg" alt="">
                                        </div>
                                        <span>{{ __('backend.auth_login.i_am') }}</span> {{ __('backend.auth_login.manager') }}
                                    </label>

                                    <label class="btn {{ old('role') == 'Supervisor' ? 'active' : '' }}">
                                        <input type="radio" name="role" value="Supervisor" {{ old('role') == 'Supervisor' ? 'checked' : '' }}>
                                        <div class="icon">
                                            <img src="{{ asset('vendors/images/person.svg') }}" class="svg" alt="">
                                        </div>
                                        <span>{{ __('backend.auth_login.i_am') }}</span> {{ __('backend.auth_login.supervisor') }}
                                    </label>

                                    @error('role')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="input-group custom mt-3">
                                <input type="text" name="login_id" class="form-control form-control-lg" placeholder="{{ __('backend.auth_login.email_or_username') }}" value="{{ old('login_id') }}">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            @error('login_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <div class="input-group custom mt-3">
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="{{ __('backend.auth_login.password_placeholder') }}">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
                                </div>
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                            <div class="row pb-30 mt-3">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="remember" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">{{ __('backend.auth_login.remember') }}</label>
                                    </div>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="#" id="forgot-password-toggle">{{ __('backend.auth_login.forgot_password') }}</a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('backend.auth_login.sign_in') }}</button>
                                    <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">{{ __('backend.auth_login.or') }}</div>
                                    <a class="btn btn-outline-primary btn-lg btn-block" href="{{ route('admin.register.show') }}">
                                        {{ __('backend.auth_login.register_to_create_account') }}
                                    </a>
                                </div>
                            </div>

                            @if(session('error'))
                                <div class="text-danger mt-2 text-center">{{ session('error') }}</div>
                            @endif
                        </form>
                    </div>

                    <div id="forgot-password-form" style="display:none;">
                        <div class="login-title">
                            <h2 class="text-center text-primary">{{ __('backend.auth_login.reset_password') }}</h2>
                            <p class="text-center">{{ __('backend.auth_login.reset_password_subtitle') }}</p>
                        </div>

                        @if(session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="input-group custom mt-3">
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="{{ __('backend.auth_login.your_email') }}" required>
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">{{ __('backend.auth_login.send_reset_link') }}</button>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <a href="#" id="back-to-login">{{ __('backend.auth_login.back_to_login') }}</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('auth_scripts')
<script>
    document.getElementById('forgot-password-toggle').addEventListener('click', function(e){
        e.preventDefault();
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('forgot-password-form').style.display = 'block';
    });

    document.getElementById('back-to-login').addEventListener('click', function(e){
        e.preventDefault();
        document.getElementById('forgot-password-form').style.display = 'none';
        document.getElementById('login-form').style.display = 'block';
    });
</script>
@endpush