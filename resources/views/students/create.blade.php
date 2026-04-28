@extends('layouts.app')

@section('title', __('backend.students_create.title'))

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    :root {
        --page-bg: #f5f7fb;
        --card-bg: #ffffff;
        --text-main: #172033;
        --text-soft: #7b8497;
        --border-color: #e8ecf4;
        --primary-color: #4e73df;
        --primary-soft: rgba(78, 115, 223, 0.10);
        --success-color: #1cc88a;
        --success-soft: rgba(28, 200, 138, 0.12);
        --danger-color: #e74a3b;
        --danger-soft: rgba(231, 74, 59, 0.12);
        --warning-color: #f6c23e;
        --warning-soft: rgba(246, 194, 62, 0.14);
        --shadow-sm: 0 8px 20px rgba(18, 38, 63, 0.06);
        --shadow-md: 0 14px 36px rgba(18, 38, 63, 0.10);
    }

    body {
        background: var(--page-bg);
    }

    .student-page {
        padding: 10px 0 24px;
    }

    .page-header-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9fbff 100%);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        padding: 26px 28px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .page-subtitle {
        margin: 8px 0 0;
        color: var(--text-soft);
        font-size: 0.96rem;
    }

    .custom-alert {
        border: none;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
    }

    .main-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .card-head {
        padding: 22px 24px 14px;
        border-bottom: 1px solid rgba(232, 236, 244, 0.8);
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .card-title {
        margin: 0;
        font-size: 1.08rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .card-subtitle {
        margin-top: 6px;
        color: var(--text-soft);
        font-size: 0.9rem;
    }

    .card-body-custom {
        padding: 24px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.96rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 18px;
    }

    .section-title .icon-wrap {
        width: 34px;
        height: 34px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-soft);
        color: var(--primary-color);
        font-size: 0.95rem;
    }

    .section-block {
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 20px;
        background: #fff;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.7);
    }

    .form-label {
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        min-height: 48px;
        border-radius: 14px;
        border: 1px solid #dfe5ef;
        box-shadow: none;
        padding: 12px 14px;
        transition: all 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: rgba(78, 115, 223, 0.5);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.12);
    }

    .field-hint {
        font-size: 0.82rem;
        color: var(--text-soft);
        margin-top: 6px;
    }

    .actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 22px;
        border-top: 1px solid rgba(232, 236, 244, 0.8);
        margin-top: 24px;
    }

    .btn-soft-back {
        background: #eef2f8;
        color: #344054;
        border: none;
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 700;
    }

    .btn-create-student {
        background: linear-gradient(135deg, var(--success-color), #26d79a);
        color: #fff;
        border: none;
        border-radius: 14px;
        padding: 12px 22px;
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(28, 200, 138, 0.18);
        transition: all 0.2s ease;
    }

    .btn-create-student:hover {
        transform: translateY(-2px);
        color: #fff;
    }

    .error-list {
        margin: 0;
        padding-left: 1rem;
    }

    @media (max-width: 768px) {
        .page-header-card,
        .card-body-custom {
            padding: 20px;
        }

        .actions-bar {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .actions-bar .btn,
        .actions-bar a {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="container-fluid student-page">
    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.students_create.page_title') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.students_create.page_subtitle') }}
                </p>
            </div>

            <div>
                <a href="{{ route('admin.students.index') }}" class="btn btn-soft-back">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('backend.students_create.back_to_students') }}
                </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger custom-alert mb-4" role="alert">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    <div class="fw-bold mb-1">{{ __('backend.students_create.errors_review') }}</div>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.students.store') }}" method="POST" id="studentCreateForm">
        @csrf

        <div class="main-card">
            <div class="card-head">
                <h2 class="card-title">{{ __('backend.students_create.form_title') }}</h2>
                <div class="card-subtitle">{{ __('backend.students_create.form_subtitle') }}</div>
            </div>

            <div class="card-body-custom">
                <div class="section-block mb-4">
                    <div class="section-title">
                        <span class="icon-wrap"><i class="bi bi-person-badge"></i></span>
                        {{ __('backend.students_create.basic_user_information') }}
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('backend.students_create.name') }}</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('backend.students_create.email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('backend.students_create.password') }}</label>
                            <div class="position-relative">
                                <input type="password" name="password" id="passwordField" class="form-control pe-5" required>
                                <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent text-muted me-2" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="field-hint">{{ __('backend.students_create.password_hint') }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('backend.students_create.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="active" {{ old('status')=='active' ? 'selected' : '' }}>{{ __('backend.students_create.status_active') }}</option>
                                <option value="pending" {{ old('status')=='pending' ? 'selected' : '' }}>{{ __('backend.students_create.status_pending') }}</option>
                                <option value="inactive" {{ old('status')=='inactive' ? 'selected' : '' }}>{{ __('backend.students_create.status_inactive') }}</option>
                                <option value="disabled" {{ old('status')=='disabled' ? 'selected' : '' }}>{{ __('backend.students_create.status_disabled') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="section-block">
                    <div class="section-title">
                        <span class="icon-wrap"><i class="bi bi-mortarboard"></i></span>
                        {{ __('backend.students_create.student_academic_information') }}
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('backend.students_create.major') }}</label>
                            <input type="text" name="major" class="form-control" value="{{ old('major') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('backend.students_create.phone') }}</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('backend.students_create.address') }}</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                        </div>
                    </div>
                </div>

                <div class="actions-bar">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-soft-back">
                        <i class="bi bi-arrow-left me-2"></i>{{ __('backend.students_create.back') }}
                    </a>

                    <button type="submit" class="btn btn-create-student" id="submitCreateBtn">
                        <i class="bi bi-person-plus-fill me-2"></i>{{ __('backend.students_create.create_student') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordField = document.getElementById('passwordField');
        const togglePassword = document.getElementById('togglePassword');
        const submitBtn = document.getElementById('submitCreateBtn');
        const form = document.getElementById('studentCreateForm');

        if (togglePassword && passwordField) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordField.getAttribute('type') === 'password';
                passwordField.setAttribute('type', isPassword ? 'text' : 'password');
                this.innerHTML = isPassword
                    ? '<i class="bi bi-eye-slash"></i>'
                    : '<i class="bi bi-eye"></i>';
            });
        }

        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __('backend.students_create.creating') }}';
            });
        }
    });
</script>
@endsection