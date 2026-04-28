@extends('supervisor.layout.app_super')

@section('title', __('backend.supervisor_profile.title'))

@section('content')
<style>
    .profile-page .page-header-card {
        background: linear-gradient(135deg, #0d1b4c 0%, #1b00ff 100%);
        border-radius: 20px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 12px 30px rgba(27, 0, 255, 0.18);
        margin-bottom: 24px;
    }

    .profile-page .page-header-card h3 {
        margin: 0;
        font-weight: 700;
        color: #fff;
    }

    .profile-page .page-header-card p {
        margin: 8px 0 0;
        opacity: 0.9;
    }

    .profile-page .profile-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .profile-page .profile-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .profile-page .profile-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .profile-page .profile-card-header small {
        color: #64748b;
    }

    .profile-page .profile-card-body {
        padding: 24px;
    }

    .profile-page .info-mini-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2ff;
        height: 100%;
        transition: 0.3s ease;
    }

    .profile-page .info-mini-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.10);
    }

    .profile-page .info-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 14px;
        color: #fff;
    }

    .profile-page .info-icon.primary { background: linear-gradient(135deg, #1b00ff, #4f46e5); }
    .profile-page .info-icon.info { background: linear-gradient(135deg, #0891b2, #06b6d4); }

    .profile-page .info-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .profile-page .info-value {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
        word-break: break-word;
    }

    .profile-page .form-group-modern {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 16px;
        height: 100%;
    }

    .profile-page .form-group-modern label {
        font-size: 13px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 10px;
        display: block;
    }

    .profile-page .form-control {
        border-radius: 12px;
        min-height: 48px;
        border: 1px solid #dbe3ee;
        box-shadow: none;
        font-size: 14px;
    }

    .profile-page .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.18rem rgba(99, 102, 241, 0.12);
    }

    .profile-page .section-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 16px;
    }

    .profile-page .password-note {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
        border-radius: 14px;
        padding: 14px 16px;
        font-size: 13px;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .profile-page .btn-save-profile {
        background: linear-gradient(135deg, #1b00ff, #4338ca);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 12px 22px;
        font-weight: 700;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(27, 0, 255, 0.16);
    }

    .profile-page .btn-save-profile:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 14px 24px rgba(27, 0, 255, 0.22);
    }

    .profile-page .btn-outline-profile {
        border: 1px solid #dbe3ee;
        color: #334155;
        background: #fff;
        border-radius: 12px;
        padding: 12px 18px;
        font-weight: 700;
        text-decoration: none;
        transition: all .25s ease;
    }

    .profile-page .btn-outline-profile:hover {
        text-decoration: none;
        color: #1b00ff;
        border-color: #c7d2fe;
        background: #eef2ff;
    }

    .profile-page .profile-avatar-wrapper {
        display: flex;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 24px;
        padding: 18px;
        border-radius: 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }

    .profile-page .profile-avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
        background: #fff;
    }

    .profile-page .avatar-meta h6 {
        margin: 0 0 6px;
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }

    .profile-page .avatar-meta p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 profile-page">
    <div class="min-height-200px">

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 14px;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 14px;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="page-header-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                <div>
                    <h3>{{ __('backend.supervisor_profile.page_title') }}</h3>
                    <p>{{ __('backend.supervisor_profile.page_subtitle') }}</p>
                </div>

                <div>
                    <a href="{{ route('supervisor.dashboard') }}" class="btn-outline-profile">
                        <i class="fa fa-home mr-1"></i> {{ __('backend.supervisor_profile.dashboard') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-6 col-md-6 mb-3">
                <div class="info-mini-card">
                    <div class="info-icon primary">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="info-label">{{ __('backend.supervisor_profile.current_name') }}</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-3">
                <div class="info-mini-card">
                    <div class="info-icon info">
                        <i class="fa fa-envelope"></i>
                    </div>
                    <div class="info-label">{{ __('backend.supervisor_profile.current_email') }}</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <div class="profile-card-header">
                <div>
                    <h5>{{ __('backend.supervisor_profile.update_supervisor_profile') }}</h5>
                    <small>{{ __('backend.supervisor_profile.update_supervisor_profile_subtitle') }}</small>
                </div>
            </div>

            <div class="profile-card-body">
                <div class="password-note">
                    {{ __('backend.supervisor_profile.password_note') }}
                </div>

                <form action="{{ route('supervisor.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="profile-avatar-wrapper">
                        <img
                            src="{{ !empty($user->profile_image) ? asset('storage/' . $user->profile_image) : asset('vendors/images/photo1.jpg') }}"
                            alt="{{ __('backend.supervisor_profile.supervisor_avatar') }}"
                            class="profile-avatar"
                        >

                        <div class="avatar-meta">
                            <h6>{{ __('backend.supervisor_profile.profile_photo') }}</h6>
                            <p>{{ __('backend.supervisor_profile.profile_photo_text') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group-modern">
                                <label>{{ __('backend.supervisor_profile.full_name') }}</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                    placeholder="{{ __('backend.supervisor_profile.full_name_placeholder') }}"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group-modern">
                                <label>{{ __('backend.supervisor_profile.email_address') }}</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    placeholder="{{ __('backend.supervisor_profile.email_address_placeholder') }}"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-group-modern">
                                <label>{{ __('backend.supervisor_profile.profile_image') }}</label>
                                <input
                                    type="file"
                                    name="profile_image"
                                    class="form-control @error('profile_image') is-invalid @enderror"
                                    accept="image/*"
                                >
                                @error('profile_image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group-modern">
                                <label>{{ __('backend.supervisor_profile.new_password') }}</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="{{ __('backend.supervisor_profile.new_password_placeholder') }}"
                                >
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group-modern">
                                <label>{{ __('backend.supervisor_profile.confirm_password') }}</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="{{ __('backend.supervisor_profile.confirm_password_placeholder') }}"
                                >
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <button class="btn-save-profile" type="submit">
                                <i class="fa fa-save mr-1"></i> {{ __('backend.supervisor_profile.update_profile') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection