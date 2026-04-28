@extends('layouts.app')

@section('title', __('backend.announcements_history.title'))

@section('content')
<div class="container-fluid py-4 px-lg-5">

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark">{{ __('backend.announcements_history.page_title') }}</h3>
            <p class="text-muted small mb-0">
                {{ __('backend.announcements_history.page_subtitle') }}
            </p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.announcements.index') }}"
               class="btn btn-light border rounded-3 px-4 py-2 fw-semibold">
                <i class="bi bi-arrow-left me-2"></i> {{ __('backend.announcements_history.back') }}
            </a>

            <a href="{{ route('admin.announcements.create') }}"
               class="btn btn-primary px-4 py-2 rounded-3 fw-semibold shadow-sm">
                <i class="bi bi-plus-lg me-2"></i> {{ __('backend.announcements_history.new_announcement') }}
            </a>
        </div>
    </div>

    {{-- Analytics --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl">
            <div class="analytics-card">
                <span class="analytics-label">{{ __('backend.announcements_history.total') }}</span>
                <h4 class="analytics-value">{{ $analytics['total'] }}</h4>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="analytics-card">
                <span class="analytics-label">{{ __('backend.announcements_history.active') }}</span>
                <h4 class="analytics-value text-success">{{ $analytics['active'] }}</h4>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="analytics-card">
                <span class="analytics-label">{{ __('backend.announcements_history.scheduled') }}</span>
                <h4 class="analytics-value text-info">{{ $analytics['scheduled'] }}</h4>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="analytics-card">
                <span class="analytics-label">{{ __('backend.announcements_history.expired') }}</span>
                <h4 class="analytics-value text-dark">{{ $analytics['expired'] }}</h4>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="analytics-card">
                <span class="analytics-label">{{ __('backend.announcements_history.pinned') }}</span>
                <h4 class="analytics-value text-warning">{{ $analytics['pinned'] }}</h4>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-card mb-4">
        <form method="GET" action="{{ route('admin.announcements.history') }}">
            <div class="row g-3 align-items-end">
                <div class="col-lg-4">
                    <label class="form-label fw-semibold text-dark">{{ __('backend.announcements_history.search') }}</label>
                    <input type="text"
                           name="search"
                           class="form-control rounded-3"
                           placeholder="{{ __('backend.announcements_history.search_placeholder') }}"
                           value="{{ request('search') }}">
                </div>

                <div class="col-md-4 col-lg-3">
                    <label class="form-label fw-semibold text-dark">{{ __('backend.announcements_history.status') }}</label>
                    <select name="filter" class="form-select rounded-3">
                        <option value="">{{ __('backend.announcements_history.all_statuses') }}</option>
                        <option value="active" {{ request('filter') === 'active' ? 'selected' : '' }}>{{ __('backend.announcements_history.active') }}</option>
                        <option value="scheduled" {{ request('filter') === 'scheduled' ? 'selected' : '' }}>{{ __('backend.announcements_history.scheduled') }}</option>
                        <option value="expired" {{ request('filter') === 'expired' ? 'selected' : '' }}>{{ __('backend.announcements_history.expired') }}</option>
                        <option value="disabled" {{ request('filter') === 'disabled' ? 'selected' : '' }}>{{ __('backend.announcements_history.disabled') }}</option>
                        <option value="pinned" {{ request('filter') === 'pinned' ? 'selected' : '' }}>{{ __('backend.announcements_history.pinned') }}</option>
                    </select>
                </div>

                <div class="col-md-4 col-lg-3">
                    <label class="form-label fw-semibold text-dark">{{ __('backend.announcements_history.audience') }}</label>
                    <select name="audience" class="form-select rounded-3">
                        <option value="">{{ __('backend.announcements_history.all_audiences') }}</option>
                        <option value="all" {{ request('audience') === 'all' ? 'selected' : '' }}>{{ __('backend.announcements_history.all') }}</option>
                        <option value="students" {{ request('audience') === 'students' ? 'selected' : '' }}>{{ __('backend.announcements_history.students') }}</option>
                        <option value="investors" {{ request('audience') === 'investors' ? 'selected' : '' }}>{{ __('backend.announcements_history.investors') }}</option>
                        <option value="supervisors" {{ request('audience') === 'supervisors' ? 'selected' : '' }}>{{ __('backend.announcements_history.supervisors') }}</option>
                    </select>
                </div>

                <div class="col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-3 w-100">
                        <i class="bi bi-funnel me-1"></i> {{ __('backend.announcements_history.filter') }}
                    </button>

                    <a href="{{ route('admin.announcements.history') }}" class="btn btn-light border rounded-3 w-100">
                        {{ __('backend.announcements_history.reset') }}
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- List --}}
    <div class="row g-4">
        @forelse($announcements as $announcement)

            @php
                if (! $announcement->is_active) {
                    $status = [__('backend.announcements_history.disabled'), 'secondary'];
                } elseif ($announcement->publish_at && $announcement->publish_at->isFuture()) {
                    $status = [__('backend.announcements_history.scheduled'), 'info'];
                } elseif ($announcement->expires_at && $announcement->expires_at->isPast()) {
                    $status = [__('backend.announcements_history.expired'), 'dark'];
                } else {
                    $status = [__('backend.announcements_history.active'), 'success'];
                }
            @endphp

            <div class="col-lg-6">
                <div class="announcement-history-card h-100">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            @if($announcement->is_pinned)
                                <span class="badge badge-pinned">
                                    <i class="fa fa-thumbtack me-1"></i> {{ __('backend.announcements_history.pinned') }}
                                </span>
                            @endif

                            <span class="badge bg-{{ $status[1] }}">
                                {{ $status[0] }}
                            </span>

                            <span class="badge badge-audience">
                                {{ ucfirst($announcement->audience) }}
                            </span>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-light btn-sm border rounded-3" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.announcements.show', $announcement) }}">
                                        <i class="bi bi-eye me-2"></i> {{ __('backend.announcements_history.view') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.announcements.edit', $announcement) }}">
                                        <i class="bi bi-pencil me-2"></i> {{ __('backend.announcements_history.edit') }}
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item text-danger"
                                            onclick="return confirm('{{ __('backend.announcements_history.confirm_delete') }}')">
                                            <i class="bi bi-trash me-2"></i> {{ __('backend.announcements_history.delete') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-2">
                        {{ $announcement->title }}
                    </h5>

                    <p class="text-muted small mb-3">
                        {{ \Illuminate\Support\Str::limit($announcement->body, 120) }}
                    </p>

                    <div class="announcement-meta d-flex flex-wrap gap-3">
                        <div>
                            <span class="meta-label">{{ __('backend.announcements_history.publish') }}</span>
                            <span class="meta-value">
                                {{ $announcement->publish_at?->format('M d, Y') ?? '-' }}
                            </span>
                        </div>

                        <div>
                            <span class="meta-label">{{ __('backend.announcements_history.expires') }}</span>
                            <span class="meta-value">
                                {{ $announcement->expires_at?->format('M d, Y') ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-megaphone fs-1 text-muted mb-3"></i>
                    <p class="text-muted mb-2">{{ __('backend.announcements_history.no_announcements_found') }}</p>
                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">
                        {{ __('backend.announcements_history.create_first_announcement') }}
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $announcements->links() }}
    </div>
</div>

<style>
.announcement-history-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 12px 30px rgba(15,23,42,0.05);
    transition: all 0.25s ease;
}

.announcement-history-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 50px rgba(15,23,42,0.08);
}

.analytics-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 18px 20px;
    box-shadow: 0 10px 24px rgba(15,23,42,0.04);
    height: 100%;
}

.analytics-label {
    display: block;
    font-size: 12px;
    color: #64748b;
    font-weight: 600;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.analytics-value {
    font-size: 28px;
    font-weight: 800;
    margin: 0;
    color: #0f172a;
}

.filter-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 10px 24px rgba(15,23,42,0.04);
}

.badge {
    font-size: 11px;
    padding: 6px 10px;
    border-radius: 999px;
}

.badge-pinned {
    background: rgba(245,158,11,0.15);
    color: #f59e0b;
}

.badge-audience {
    background: #f1f5f9;
    color: #0f172a;
}

.meta-label {
    font-size: 11px;
    color: #64748b;
    display: block;
}

.meta-value {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
}

.container-fluid {
    max-width: 1100px;
}
</style>
@endsection