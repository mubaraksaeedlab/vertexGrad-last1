@extends('layouts.app')

@section('title', __('backend.manager_profile.page_title'))

@section('content')
<div class="container-fluid py-4 profile-page">
    <style>
        .profile-page .profile-header-card {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            color: #fff;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.18);
        }

        .profile-page .profile-header-card .overlay {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(2px);
        }

        .profile-page .profile-avatar {
            width: 130px;
            height: 130px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.95);
            box-shadow: 0 12px 30px rgba(0,0,0,0.18);
            background: #fff;
        }

        .profile-page .profile-stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255,255,255,0.14);
            font-size: 13px;
            font-weight: 600;
            color: #fff;
        }

        .profile-page .content-card {
            border: 0;
            border-radius: 22px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        .profile-page .section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1rem;
        }

        .profile-page .form-label {
            font-weight: 600;
            color: #334155;
        }

        .profile-page .form-control {
            border-radius: 14px;
            min-height: 48px;
            border: 1px solid #dbe3ef;
            box-shadow: none;
        }

        .profile-page .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.12);
        }

        .profile-page .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 0;
            border-bottom: 1px solid #eef2f7;
        }

        .profile-page .info-item:last-child {
            border-bottom: 0;
        }

        .profile-page .info-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #eff6ff;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .profile-page .btn-primary,
        .profile-page .btn-danger {
            border-radius: 14px;
            font-weight: 600;
            padding: 10px 18px;
        }

        .profile-page .input-group .btn {
            border-radius: 0 14px 14px 0;
        }
    </style>
    
    <div class="card profile-header-card mb-4">
        <div class="card-body p-4 p-lg-5 overlay">
            <div class="row align-items-center">
                <div class="col-lg-8 d-flex flex-column flex-md-row align-items-center align-items-md-start gap-4">
                    <img
                        src="{{ !empty($user->profile_image) ? asset('storage/' . $user->profile_image) : asset('vendors/images/photo1.jpg') }}"
                        alt="{{ __('backend.manager_profile.manager_avatar') }}"
                        class="profile-avatar"
                    >

                    <div class="text-center text-md-start">
                        <div class="mb-2">
                            <span class="profile-stat-badge">
                                <i class="bi bi-person-badge-fill"></i> {{ __('backend.manager_profile.manager_account') }}
                            </span>
                        </div>

                        <h2 class="mb-2 fw-bold">{{ $user->name }}</h2>
                        <p class="mb-1 opacity-75">{{ $user->role ?? __('backend.manager_profile.manager') }}</p>
                        <p class="mb-1 opacity-75">{{ $user->email }}</p>

                        @if(!empty($user->manager?->department))
                            <p class="mb-0 opacity-75">
                                <i class="bi bi-diagram-3 me-1"></i>{{ $user->manager->department }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                    <a href="{{ route('manager.dashboard') }}" class="btn btn-light rounded-pill px-4">
                        <i class="bi bi-speedometer2 me-2"></i>{{ __('backend.manager_profile.back_to_dashboard') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card content-card h-100">
                <div class="card-body p-4">
                    <h5 class="section-title">{{ __('backend.manager_profile.account_overview') }}</h5>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-person"></i>
                        </div>
                        <div>
                            <div class="text-muted small">{{ __('backend.manager_profile.full_name') }}</div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <div class="text-muted small">{{ __('backend.manager_profile.email_address') }}</div>
                            <div class="fw-semibold">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <div>
                            <div class="text-muted small">{{ __('backend.manager_profile.department') }}</div>
                            <div class="fw-semibold">{{ $user->manager?->department ?: __('backend.manager_profile.not_assigned') }}</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <div class="text-muted small">{{ __('backend.manager_profile.last_login') }}</div>
                            <div class="fw-semibold">
                                {{ $user->manager?->last_login ? \Carbon\Carbon::parse($user->manager->last_login)->format('M d, Y h:i A') : __('backend.manager_profile.no_record_available') }}
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <div>
                            <div class="text-muted small">{{ __('backend.manager_profile.role') }}</div>
                            <div class="fw-semibold">{{ $user->role ?? __('backend.manager_profile.manager') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card content-card mb-4">
                <div class="card-body p-4">
                    <h5 class="section-title">{{ __('backend.manager_profile.update_profile_information') }}</h5>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('backend.manager_profile.full_name') }}</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}"
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('backend.manager_profile.email_address') }}</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">{{ __('backend.manager_profile.department') }}</label>
                                <input
                                    type="text"
                                    name="department"
                                    class="form-control @error('department') is-invalid @enderror"
                                    value="{{ old('department', $user->manager?->department) }}"
                                >
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">{{ __('backend.manager_profile.profile_image') }}</label>
                                <input
                                    type="file"
                                    name="profile_image"
                                    class="form-control @error('profile_image') is-invalid @enderror"
                                >
                                @error('profile_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2-circle me-2"></i>{{ __('backend.manager_profile.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card content-card">
                <div class="card-body p-4">
                    <h5 class="section-title">{{ __('backend.manager_profile.change_password') }}</h5>

                    <form action="{{ route('admin.profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">{{ __('backend.manager_profile.current_password') }}</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        name="current_password"
                                        id="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                    >
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('backend.manager_profile.new_password') }}</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                    >
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">{{ __('backend.manager_profile.confirm_new_password') }}</label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="form-control"
                                    >
                                    <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password_confirmation">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-shield-lock me-2"></i>{{ __('backend.manager_profile.update_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-password').forEach(function (button) {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    });
</script>
@endsection