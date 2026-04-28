@extends('layouts.app')

@section('title', __('backend.auth_role_policies_show.title'))

@section('content')
<style>
    .role-auth-policy-page .hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #3b82f6 100%);
        border-radius: 24px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        margin-bottom: 24px;
    }

    .role-auth-policy-page .hero-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #fff;
    }

    .role-auth-policy-page .hero-text {
        font-size: 14px;
        opacity: .92;
        margin-bottom: 0;
        max-width: 820px;
        line-height: 1.8;
    }

    .role-auth-policy-page .section-card {
        background: #fff;
        border-radius: 22px;
        border: 1px solid #eef2f7;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .role-auth-policy-page .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .role-auth-policy-page .section-header h4,
    .role-auth-policy-page .section-header h5 {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
    }

    .role-auth-policy-page .section-subtext {
        margin-top: 6px;
        color: #64748b;
        font-size: 13px;
    }

    .role-auth-policy-page .section-body {
        padding: 24px;
    }

    .role-auth-policy-page .info-box {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        height: 100%;
    }

    .role-auth-policy-page .info-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .role-auth-policy-page .info-value {
        color: #0f172a;
        font-size: 16px;
        font-weight: 800;
        line-height: 1.4;
    }

    .role-auth-policy-page .save-btn {
        border: none;
        border-radius: 14px;
        padding: 12px 22px;
        font-weight: 800;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 14px 24px rgba(37, 99, 235, 0.20);
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 role-auth-policy-page">
    <div class="min-height-200px">
        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 14px;">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="hero-card">
            <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
                <div>
                    <div class="hero-title">{{ __('backend.auth_role_policies_show.page_title') }}</div>
                    <p class="hero-text">
                        {{ __('backend.auth_role_policies_show.page_subtitle_before') }}
                        <strong>{{ $rolePolicy->role_name }}</strong>
                        {{ __('backend.auth_role_policies_show.page_subtitle_after') }}
                    </p>
                </div>

                <a href="{{ route('admin.auth-role-policies.index') }}"
                   class="btn btn-light btn-sm"
                   style="border-radius: 10px; font-weight: 700;">
                    {{ __('backend.auth_role_policies_show.back') }}
                </a>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h4>{{ __('backend.auth_role_policies_show.summary_title') }}</h4>
                <div class="section-subtext">
                    {{ __('backend.auth_role_policies_show.summary_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_role_policies_show.role_name') }}</div>
                            <div class="info-value">{{ $rolePolicy->role_name }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_role_policies_show.email_verification') }}</div>
                            <div class="info-value">{{ ucfirst($rolePolicy->email_verification_mode) }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_role_policies_show.otp_mode') }}</div>
                            <div class="info-value">{{ ucfirst($rolePolicy->otp_mode) }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_role_policies_show.trusted_devices') }}</div>
                            <div class="info-value">{{ $rolePolicy->trusted_devices_enabled ? __('backend.auth_role_policies_show.enabled') : __('backend.auth_role_policies_show.disabled') }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_role_policies_show.recovery_codes') }}</div>
                            <div class="info-value">{{ $rolePolicy->recovery_codes_enabled ? __('backend.auth_role_policies_show.enabled') : __('backend.auth_role_policies_show.disabled') }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_role_policies_show.suspicious_login_alerts') }}</div>
                            <div class="info-value">{{ $rolePolicy->suspicious_login_alerts_enabled ? __('backend.auth_role_policies_show.enabled') : __('backend.auth_role_policies_show.disabled') }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_role_policies_show.remember_me') }}</div>
                            <div class="info-value">{{ $rolePolicy->remember_me_enabled ? __('backend.auth_role_policies_show.enabled') : __('backend.auth_role_policies_show.disabled') }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_role_policies_show.emergency_bypass') }}</div>
                            <div class="info-value">{{ $rolePolicy->emergency_bypass_enabled ? __('backend.auth_role_policies_show.enabled') : __('backend.auth_role_policies_show.disabled') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h5>{{ __('backend.auth_role_policies_show.edit_title') }}</h5>
                <div class="section-subtext">
                    {{ __('backend.auth_role_policies_show.edit_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                <form action="{{ route('admin.auth-role-policies.update', $rolePolicy->id) }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_role_policies_show.email_verification_mode') }}</label>
                            <select name="email_verification_mode" class="form-control" style="border-radius: 12px;">
                                <option value="required" {{ $rolePolicy->email_verification_mode === 'required' ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.required') }}</option>
                                <option value="optional" {{ $rolePolicy->email_verification_mode === 'optional' ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.optional') }}</option>
                                <option value="disabled" {{ $rolePolicy->email_verification_mode === 'disabled' ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_role_policies_show.otp_mode') }}</label>
                            <select name="otp_mode" class="form-control" style="border-radius: 12px;">
                                <option value="required" {{ $rolePolicy->otp_mode === 'required' ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.required') }}</option>
                                <option value="optional" {{ $rolePolicy->otp_mode === 'optional' ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.optional') }}</option>
                                <option value="disabled" {{ $rolePolicy->otp_mode === 'disabled' ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_role_policies_show.trusted_devices') }}</label>
                            <select name="trusted_devices_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="1" {{ $rolePolicy->trusted_devices_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.enabled') }}</option>
                                <option value="0" {{ ! $rolePolicy->trusted_devices_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_role_policies_show.recovery_codes') }}</label>
                            <select name="recovery_codes_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="1" {{ $rolePolicy->recovery_codes_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.enabled') }}</option>
                                <option value="0" {{ ! $rolePolicy->recovery_codes_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_role_policies_show.suspicious_login_alerts') }}</label>
                            <select name="suspicious_login_alerts_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="1" {{ $rolePolicy->suspicious_login_alerts_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.enabled') }}</option>
                                <option value="0" {{ ! $rolePolicy->suspicious_login_alerts_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_role_policies_show.remember_me') }}</label>
                            <select name="remember_me_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="1" {{ $rolePolicy->remember_me_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.enabled') }}</option>
                                <option value="0" {{ ! $rolePolicy->remember_me_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_role_policies_show.emergency_bypass') }}</label>
                            <select name="emergency_bypass_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="1" {{ $rolePolicy->emergency_bypass_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.enabled') }}</option>
                                <option value="0" {{ ! $rolePolicy->emergency_bypass_enabled ? 'selected' : '' }}>{{ __('backend.auth_role_policies_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_role_policies_show.notes') }}</label>
                            <textarea name="notes" rows="4" class="form-control" style="border-radius: 12px;">{{ old('notes', $rolePolicy->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary save-btn">
                            {{ __('backend.auth_role_policies_show.save_role_policy') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection