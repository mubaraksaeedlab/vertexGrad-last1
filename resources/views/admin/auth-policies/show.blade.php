@extends('layouts.app')

@section('title', __('backend.auth_policy_show.page_title'))

@section('content')
<style>
    .auth-policy-page .hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 55%, #3b82f6 100%);
        border-radius: 24px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        position: relative;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .auth-policy-page .hero-content {
        position: relative;
        z-index: 2;
    }

    .auth-policy-page .hero-title {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #fff;
    }

    .auth-policy-page .hero-text {
        font-size: 14px;
        opacity: .92;
        margin-bottom: 0;
        max-width: 820px;
        line-height: 1.8;
    }

    .auth-policy-page .section-card {
        background: #fff;
        border-radius: 22px;
        border: 1px solid #eef2f7;
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .auth-policy-page .section-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
    }

    .auth-policy-page .section-header h4,
    .auth-policy-page .section-header h5 {
        margin: 0;
        font-weight: 800;
        color: #0f172a;
    }

    .auth-policy-page .section-subtext {
        margin-top: 6px;
        color: #64748b;
        font-size: 13px;
    }

    .auth-policy-page .section-body {
        padding: 24px;
    }

    .auth-policy-page .info-box {
        border: 1px solid #e5e7eb;
        border-radius: 18px;
        padding: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        height: 100%;
    }

    .auth-policy-page .info-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .auth-policy-page .info-value {
        color: #0f172a;
        font-size: 16px;
        font-weight: 800;
        line-height: 1.4;
    }

    .auth-policy-page .policy-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
    }

    .auth-policy-page .policy-item {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 16px;
        background: #fff;
    }

    .auth-policy-page .policy-label {
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .auth-policy-page .policy-value {
        color: #0f172a;
        font-size: 15px;
        font-weight: 800;
    }

    .auth-policy-page .save-btn {
        border: none;
        border-radius: 14px;
        padding: 12px 22px;
        font-weight: 800;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        box-shadow: 0 14px 24px rgba(37, 99, 235, 0.20);
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 auth-policy-page">
    <div class="min-height-200px">

        <div class="hero-card">
            <div class="hero-content d-flex justify-content-between align-items-start flex-wrap" style="gap: 16px;">
                <div>
                    <div class="hero-title">{{ __('backend.auth_policy_show.heading') }}</div>
                    <p class="hero-text">
                        {{ __('backend.auth_policy_show.subtitle') }}
                    </p>
                </div>

                <a href="{{ route('admin.auth-policies.index') }}"
                   class="btn btn-light btn-sm"
                   style="border-radius: 10px; font-weight: 700;">
                    {{ __('backend.auth_policy_show.back') }}
                </a>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h4>{{ __('backend.auth_policy_show.user_authentication_profile') }}</h4>
                <div class="section-subtext">
                    {{ __('backend.auth_policy_show.user_authentication_profile_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_policy_show.name') }}</div>
                            <div class="info-value">{{ $user->name }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_policy_show.email') }}</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="info-box">
                            <div class="info-label">{{ __('backend.auth_policy_show.current_role') }}</div>
                            <div class="info-value">{{ $user->role }}</div>
                        </div>
                    </div>
                </div>

                <div class="policy-grid mt-3">
                    <div class="policy-item">
                        <div class="policy-label">{{ __('backend.auth_policy_show.policy_source') }}</div>
                        <div class="policy-value">{{ ucfirst(str_replace('_', ' ', $effectivePolicy['source'] ?? 'unknown')) }}</div>
                    </div>

                    <div class="policy-item">
                        <div class="policy-label">{{ __('backend.auth_policy_show.email_verification') }}</div>
                        <div class="policy-value">{{ ucfirst($effectivePolicy['email_verification_mode']) }}</div>
                    </div>

                    <div class="policy-item">
                        <div class="policy-label">{{ __('backend.auth_policy_show.otp_mode') }}</div>
                        <div class="policy-value">{{ ucfirst($effectivePolicy['otp_mode']) }}</div>
                    </div>

                    <div class="policy-item">
                        <div class="policy-label">{{ __('backend.auth_policy_show.trusted_devices') }}</div>
                        <div class="policy-value">{{ $effectivePolicy['trusted_devices_enabled'] ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                    </div>

                    <div class="policy-item">
                        <div class="policy-label">{{ __('backend.auth_policy_show.recovery_codes') }}</div>
                        <div class="policy-value">{{ $effectivePolicy['recovery_codes_enabled'] ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                    </div>

                    <div class="policy-item">
                        <div class="policy-label">{{ __('backend.auth_policy_show.suspicious_alerts') }}</div>
                        <div class="policy-value">{{ $effectivePolicy['suspicious_login_alerts_enabled'] ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                    </div>

                    <div class="policy-item">
                        <div class="policy-label">{{ __('backend.auth_policy_show.remember_me') }}</div>
                        <div class="policy-value">{{ $effectivePolicy['remember_me_enabled'] ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                    </div>

                    <div class="policy-item">
                        <div class="policy-label">{{ __('backend.auth_policy_show.emergency_bypass') }}</div>
                        <div class="policy-value">{{ $effectivePolicy['emergency_bypass_enabled'] ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h5>{{ __('backend.auth_policy_show.role_default_policy') }}</h5>
                <div class="section-subtext">
                    {{ __('backend.auth_policy_show.role_default_policy_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                @if($rolePolicy)
                    <div class="policy-grid">
                        <div class="policy-item">
                            <div class="policy-label">{{ __('backend.auth_policy_show.email_verification') }}</div>
                            <div class="policy-value">{{ ucfirst($rolePolicy->email_verification_mode) }}</div>
                        </div>

                        <div class="policy-item">
                            <div class="policy-label">{{ __('backend.auth_policy_show.otp_mode') }}</div>
                            <div class="policy-value">{{ ucfirst($rolePolicy->otp_mode) }}</div>
                        </div>

                        <div class="policy-item">
                            <div class="policy-label">{{ __('backend.auth_policy_show.trusted_devices') }}</div>
                            <div class="policy-value">{{ $rolePolicy->trusted_devices_enabled ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                        </div>

                        <div class="policy-item">
                            <div class="policy-label">{{ __('backend.auth_policy_show.recovery_codes') }}</div>
                            <div class="policy-value">{{ $rolePolicy->recovery_codes_enabled ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                        </div>

                        <div class="policy-item">
                            <div class="policy-label">{{ __('backend.auth_policy_show.suspicious_alerts') }}</div>
                            <div class="policy-value">{{ $rolePolicy->suspicious_login_alerts_enabled ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                        </div>

                        <div class="policy-item">
                            <div class="policy-label">{{ __('backend.auth_policy_show.remember_me') }}</div>
                            <div class="policy-value">{{ $rolePolicy->remember_me_enabled ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                        </div>

                        <div class="policy-item">
                            <div class="policy-label">{{ __('backend.auth_policy_show.emergency_bypass') }}</div>
                            <div class="policy-value">{{ $rolePolicy->emergency_bypass_enabled ? __('backend.auth_policy_show.enabled') : __('backend.auth_policy_show.disabled') }}</div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-0" style="border-radius: 14px;">
                        {{ __('backend.auth_policy_show.no_role_policy_found') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">
                <h5>{{ __('backend.auth_policy_show.user_override_policy') }}</h5>
                <div class="section-subtext">
                    {{ __('backend.auth_policy_show.user_override_policy_subtitle') }}
                </div>
            </div>

            <div class="section-body">
                @php
                    $override = $user->authPolicyOverride;
                @endphp

                <form action="{{ route('admin.auth-policies.update', $user->id) }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label class="font-weight-bold d-block">{{ __('backend.auth_policy_show.use_role_defaults') }}</label>
                        <select name="use_role_defaults" class="form-control" style="border-radius: 12px;">
                            <option value="1" {{ ! $override || $override->use_role_defaults ? 'selected' : '' }}>{{ __('backend.auth_policy_show.yes') }}</option>
                            <option value="0" {{ $override && ! $override->use_role_defaults ? 'selected' : '' }}>{{ __('backend.auth_policy_show.no_use_custom_policy') }}</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_policy_show.email_verification_mode') }}</label>
                            <select name="email_verification_mode" class="form-control" style="border-radius: 12px;">
                                <option value="">{{ __('backend.auth_policy_show.use_inherited_value') }}</option>
                                <option value="required" {{ optional($override)->email_verification_mode === 'required' ? 'selected' : '' }}>{{ __('backend.auth_policy_show.required') }}</option>
                                <option value="optional" {{ optional($override)->email_verification_mode === 'optional' ? 'selected' : '' }}>{{ __('backend.auth_policy_show.optional') }}</option>
                                <option value="disabled" {{ optional($override)->email_verification_mode === 'disabled' ? 'selected' : '' }}>{{ __('backend.auth_policy_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_policy_show.otp_mode') }}</label>
                            <select name="otp_mode" class="form-control" style="border-radius: 12px;">
                                <option value="">{{ __('backend.auth_policy_show.use_inherited_value') }}</option>
                                <option value="required" {{ optional($override)->otp_mode === 'required' ? 'selected' : '' }}>{{ __('backend.auth_policy_show.required') }}</option>
                                <option value="optional" {{ optional($override)->otp_mode === 'optional' ? 'selected' : '' }}>{{ __('backend.auth_policy_show.optional') }}</option>
                                <option value="disabled" {{ optional($override)->otp_mode === 'disabled' ? 'selected' : '' }}>{{ __('backend.auth_policy_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_policy_show.trusted_devices') }}</label>
                            <select name="trusted_devices_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="">{{ __('backend.auth_policy_show.use_inherited_value') }}</option>
                                <option value="1" {{ optional($override)->trusted_devices_enabled === true ? 'selected' : '' }}>{{ __('backend.auth_policy_show.enabled') }}</option>
                                <option value="0" {{ optional($override)->trusted_devices_enabled === false ? 'selected' : '' }}>{{ __('backend.auth_policy_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_policy_show.recovery_codes') }}</label>
                            <select name="recovery_codes_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="">{{ __('backend.auth_policy_show.use_inherited_value') }}</option>
                                <option value="1" {{ optional($override)->recovery_codes_enabled === true ? 'selected' : '' }}>{{ __('backend.auth_policy_show.enabled') }}</option>
                                <option value="0" {{ optional($override)->recovery_codes_enabled === false ? 'selected' : '' }}>{{ __('backend.auth_policy_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_policy_show.suspicious_login_alerts') }}</label>
                            <select name="suspicious_login_alerts_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="">{{ __('backend.auth_policy_show.use_inherited_value') }}</option>
                                <option value="1" {{ optional($override)->suspicious_login_alerts_enabled === true ? 'selected' : '' }}>{{ __('backend.auth_policy_show.enabled') }}</option>
                                <option value="0" {{ optional($override)->suspicious_login_alerts_enabled === false ? 'selected' : '' }}>{{ __('backend.auth_policy_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_policy_show.remember_me') }}</label>
                            <select name="remember_me_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="">{{ __('backend.auth_policy_show.use_inherited_value') }}</option>
                                <option value="1" {{ optional($override)->remember_me_enabled === true ? 'selected' : '' }}>{{ __('backend.auth_policy_show.enabled') }}</option>
                                <option value="0" {{ optional($override)->remember_me_enabled === false ? 'selected' : '' }}>{{ __('backend.auth_policy_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_policy_show.emergency_bypass') }}</label>
                            <select name="emergency_bypass_enabled" class="form-control" style="border-radius: 12px;">
                                <option value="">{{ __('backend.auth_policy_show.use_inherited_value') }}</option>
                                <option value="1" {{ optional($override)->emergency_bypass_enabled === true ? 'selected' : '' }}>{{ __('backend.auth_policy_show.enabled') }}</option>
                                <option value="0" {{ optional($override)->emergency_bypass_enabled === false ? 'selected' : '' }}>{{ __('backend.auth_policy_show.disabled') }}</option>
                            </select>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="font-weight-bold">{{ __('backend.auth_policy_show.notes') }}</label>
                            <textarea name="notes" rows="4" class="form-control" style="border-radius: 12px;">{{ old('notes', optional($override)->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary save-btn">
                            {{ __('backend.auth_policy_show.save_authentication_policy') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection