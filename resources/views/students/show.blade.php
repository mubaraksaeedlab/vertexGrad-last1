@extends('layouts.app')

@section('title', __('backend.students_show.title'))

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
        --success-soft: rgba(28, 200, 138, 0.12);
        --warning-soft: rgba(246, 194, 62, 0.14);
        --danger-soft: rgba(231, 74, 59, 0.12);
        --shadow-sm: 0 8px 20px rgba(18, 38, 63, 0.06);
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

    .main-grid {
        display: grid;
        grid-template-columns: 1.2fr 0.8fr;
        gap: 24px;
    }

    .info-card {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 24px;
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        height: 100%;
    }

    .card-head {
        padding: 20px 24px 14px;
        border-bottom: 1px solid rgba(232, 236, 244, 0.8);
        background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    }

    .card-title {
        margin: 0;
        font-size: 1.04rem;
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

    .student-profile {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        background: #fafcff;
        margin-bottom: 22px;
    }

    .avatar-box {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-soft);
        color: var(--primary-color);
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .student-name {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .student-email {
        margin-top: 4px;
        color: var(--text-soft);
        font-size: 0.92rem;
        word-break: break-word;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-top: 10px;
    }

    .status-active {
        background: var(--success-soft);
        color: #0f8f60;
    }

    .status-inactive {
        background: var(--warning-soft);
        color: #9a7400;
    }

    .status-disabled {
        background: var(--danger-soft);
        color: #c7372b;
    }

    .status-pending {
        background: #edf1f7;
        color: #596579;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .detail-item {
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 16px;
        background: #fff;
    }

    .detail-label {
        display: block;
        font-size: 0.78rem;
        color: var(--text-soft);
        margin-bottom: 6px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .detail-value {
        font-size: 0.96rem;
        color: var(--text-main);
        font-weight: 700;
        word-break: break-word;
    }

    .empty-project-box {
        border: 1px dashed #d9e2f0;
        border-radius: 18px;
        padding: 28px 18px;
        text-align: center;
        background: #fbfcff;
        color: var(--text-soft);
    }

    .btn-soft-back {
        background: #eef2f8;
        color: #344054;
        border: none;
        border-radius: 14px;
        padding: 11px 18px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    @media (max-width: 991px) {
        .main-grid {
            grid-template-columns: 1fr;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .page-header-card,
        .card-body-custom {
            padding: 20px;
        }
    }
</style>

@php
    $statusClass = match($student->status) {
        'active' => 'status-active',
        'inactive' => 'status-inactive',
        'disabled' => 'status-disabled',
        'pending' => 'status-pending',
        default => 'status-pending',
    };
@endphp

<div class="container-fluid student-page">
    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.students_show.page_title') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.students_show.page_subtitle') }}
                </p>
            </div>

            <div>
                <a href="{{ route('admin.students.index') }}" class="btn-soft-back">
                    <i class="bi bi-arrow-left me-2"></i>{{ __('backend.students_show.back_to_students') }}
                </a>
            </div>
        </div>
    </div>

    <div class="main-grid">
        <div class="info-card">
            <div class="card-head">
                <h2 class="card-title">{{ __('backend.students_show.basic_information') }}</h2>
                <div class="card-subtitle">{{ __('backend.students_show.basic_information_subtitle') }}</div>
            </div>

            <div class="card-body-custom">
                <div class="student-profile">
                    <div class="avatar-box">
                        <i class="bi bi-person-fill"></i>
                    </div>

                    <div>
                        <h3 class="student-name">{{ $student->name }}</h3>
                        <div class="student-email">{{ $student->email }}</div>
                        <span class="status-badge {{ $statusClass }}">
                            {{ match($student->status) {
                                'active' => __('backend.students_show.status_active'),
                                'inactive' => __('backend.students_show.status_inactive'),
                                'disabled' => __('backend.students_show.status_disabled'),
                                'pending' => __('backend.students_show.status_pending'),
                                default => __('backend.students_show.status_pending'),
                            } }}
                        </span>
                    </div>
                </div>

                <div class="details-grid">
                    <div class="detail-item">
                        <span class="detail-label">{{ __('backend.students_show.name') }}</span>
                        <div class="detail-value">{{ $student->name }}</div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">{{ __('backend.students_show.email') }}</span>
                        <div class="detail-value">{{ $student->email }}</div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">{{ __('backend.students_show.status') }}</span>
                        <div class="detail-value">
                            {{ match($student->status) {
                                'active' => __('backend.students_show.status_active'),
                                'inactive' => __('backend.students_show.status_inactive'),
                                'disabled' => __('backend.students_show.status_disabled'),
                                'pending' => __('backend.students_show.status_pending'),
                                default => __('backend.students_show.status_pending'),
                            } }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">{{ __('backend.students_show.major') }}</span>
                        <div class="detail-value">{{ $student->student->major ?? '—' }}</div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">{{ __('backend.students_show.phone') }}</span>
                        <div class="detail-value">{{ $student->student->phone ?? '—' }}</div>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">{{ __('backend.students_show.address') }}</span>
                        <div class="detail-value">{{ $student->student->address ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-card">
            <div class="card-head">
                <h2 class="card-title">{{ __('backend.students_show.projects') }}</h2>
                <div class="card-subtitle">{{ __('backend.students_show.projects_subtitle') }}</div>
            </div>

            <div class="card-body-custom">
                <div class="empty-project-box">
                    <div class="mb-2">
                        <i class="bi bi-folder2-open fs-2"></i>
                    </div>
                    <div class="fw-bold mb-1">{{ __('backend.students_show.projects_section') }}</div>
                    <div>
                        {{-- عرض المشاريع إذا كانت مرتبطة --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.students.index') }}" class="btn-soft-back">
            <i class="bi bi-arrow-left me-2"></i>{{ __('backend.students_show.back') }}
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cards = document.querySelectorAll('.info-card');

        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(14px)';

            setTimeout(() => {
                card.style.transition = 'all 0.35s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 120 * (index + 1));
        });
    });
</script>
@endsection