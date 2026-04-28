@extends('layouts.app')

@section('title', __('backend.announcements_show.title'))

@section('content')
<div class="container-fluid py-4 px-lg-5">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark">{{ __('backend.announcements_show.page_title') }}</h3>
            <p class="text-muted small mb-0">{{ __('backend.announcements_show.page_subtitle') }}</p>
        </div>

        <a href="{{ route('admin.announcements.index') }}" class="btn btn-light border rounded-3 px-3 py-2 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> {{ __('backend.announcements_show.back') }}
        </a>
    </div>

    {{-- Hero Announcement --}}
    <div class="announcement-hero mb-4">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

            <div>
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">

                    @if($announcement->is_pinned)
                        <span class="badge badge-pinned">
                            <i class="fa fa-thumbtack me-1"></i> {{ __('backend.announcements_show.pinned') }}
                        </span>
                    @endif

                    @if($announcement->is_active)
                        <span class="badge badge-active">{{ __('backend.announcements_show.active') }}</span>
                    @else
                        <span class="badge badge-inactive">{{ __('backend.announcements_show.disabled') }}</span>
                    @endif

                    <span class="badge badge-audience">
                        {{ ucfirst($announcement->audience) }}
                    </span>

                </div>

                <h2 class="announcement-title">
                    {{ $announcement->title }}
                </h2>

                <p class="announcement-text">
                    {{ $announcement->body }}
                </p>
            </div>

        </div>

    </div>

    {{-- Info Cards --}}
    <div class="row g-4">

        <div class="col-md-6">
            <div class="info-card">
                <div class="info-label">{{ __('backend.announcements_show.publish_date') }}</div>
                <div class="info-value">
                    {{ $announcement->publish_at?->format('M d, Y • h:i A') ?? __('backend.announcements_show.not_scheduled') }}
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="info-card">
                <div class="info-label">{{ __('backend.announcements_show.expiration_date') }}</div>
                <div class="info-value">
                    {{ $announcement->expires_at?->format('M d, Y • h:i A') ?? __('backend.announcements_show.no_expiration') }}
                </div>
            </div>
        </div>

    </div>

    {{-- Actions --}}
    <div class="d-flex justify-content-end gap-2 mt-4">
        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-warning text-white rounded-3 px-4">
            <i class="bi bi-pencil me-1"></i> {{ __('backend.announcements_show.edit') }}
        </a>
    </div>

</div>

<style>

    .announcement-hero {
        background: linear-gradient(135deg, rgba(27,0,255,0.08), rgba(6,182,212,0.06));
        border: 1px solid rgba(27,0,255,0.12);
        border-radius: 24px;
        padding: 28px;
        box-shadow: 0 20px 50px rgba(15,23,42,0.08);
    }

    .announcement-title {
        font-size: 26px;
        font-weight: 800;
        margin-bottom: 12px;
        color: #0f172a;
    }

    .announcement-text {
        font-size: 15px;
        color: #475569;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .badge {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 999px;
        font-weight: 600;
    }

    .badge-pinned {
        background: rgba(245,158,11,0.15);
        color: #f59e0b;
    }

    .badge-active {
        background: rgba(34,197,94,0.15);
        color: #22c55e;
    }

    .badge-inactive {
        background: rgba(100,116,139,0.15);
        color: #64748b;
    }

    .badge-audience {
        background: #f1f5f9;
        color: #0f172a;
    }

    .info-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 30px rgba(15,23,42,0.05);
    }

    .info-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 6px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .info-value {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .container-fluid {
        max-width: 1000px;
    }

</style>

@endsection