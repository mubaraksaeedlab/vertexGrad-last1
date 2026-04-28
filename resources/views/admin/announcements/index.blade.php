@extends('layouts.app')

@section('title', __('backend.announcements_index.title'))

@section('content')
<div class="container-fluid py-4 px-lg-5">

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark">{{ __('backend.announcements_index.page_title') }}</h3>
            <p class="text-muted mb-0 small">{{ __('backend.announcements_index.page_subtitle') }}</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.announcements.history') }}"
               class="btn btn-light border rounded-3 px-4 py-2 fw-semibold history-btn">
                <i class="bi bi-clock-history me-2"></i> {{ __('backend.announcements_index.history') }}
            </a>

            <a href="{{ route('admin.announcements.create') }}"
               class="btn btn-primary px-4 py-2 rounded-3 fw-semibold shadow-sm create-btn">
                <i class="bi bi-plus-lg me-2"></i> {{ __('backend.announcements_index.create_announcement') }}
            </a>
        </div>
    </div>

    {{-- Card --}}
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="announcement-header-gradient"></div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table align-middle custom-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('backend.announcements_index.title_column') }}</th>
                            <th>{{ __('backend.announcements_index.audience') }}</th>
                            <th>{{ __('backend.announcements_index.pinned') }}</th>
                            <th>{{ __('backend.announcements_index.status') }}</th>
                            <th>{{ __('backend.announcements_index.publish') }}</th>
                            <th>{{ __('backend.announcements_index.expire') }}</th>
                            <th class="text-end">{{ __('backend.announcements_index.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">
                                        {{ $announcement->title }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill audience-badge">
                                        {{ ucfirst($announcement->audience) }}
                                    </span>
                                </td>

                                <td>
                                    @if($announcement->is_pinned)
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill pinned-badge">
                                            <i class="fa fa-thumbtack me-1"></i> {{ __('backend.announcements_index.pinned') }}
                                        </span>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>

                                <td>
                                    @if($announcement->is_active)
                                        <span class="badge bg-success px-3 py-2 rounded-pill status-badge">
                                            {{ __('backend.announcements_index.active') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary px-3 py-2 rounded-pill status-badge">
                                            {{ __('backend.announcements_index.disabled') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-muted small">
                                    {{ $announcement->publish_at?->format('M d, Y') ?? '-' }}
                                </td>

                                <td class="text-muted small">
                                    {{ $announcement->expires_at?->format('M d, Y') ?? '-' }}
                                </td>

                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                        <a href="{{ route('admin.announcements.show', $announcement) }}"
                                           class="btn btn-light border btn-sm rounded-3 action-btn">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.announcements.edit', $announcement) }}"
                                           class="btn btn-warning btn-sm rounded-3 text-white action-btn">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.announcements.destroy', $announcement) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm rounded-3 action-btn"
                                                    onclick="return confirm('{{ __('backend.announcements_index.confirm_delete') }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state text-center py-5">
                                        <div class="empty-state-icon mb-3">
                                            <i class="bi bi-megaphone"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-2">{{ __('backend.announcements_index.no_announcements_found') }}</h6>
                                        <p class="text-muted mb-3">{{ __('backend.announcements_index.no_announcements_subtitle') }}</p>
                                        <a href="{{ route('admin.announcements.create') }}"
                                           class="btn btn-primary btn-sm rounded-3 px-4">
                                            {{ __('backend.announcements_index.create_first_announcement') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>

</div>

<style>
    .announcement-header-gradient {
        height: 6px;
        background: linear-gradient(90deg, #1b00ff, #4f46e5, #06b6d4);
    }

    .card.shadow-lg {
        box-shadow: 0 25px 60px rgba(15, 23, 42, 0.08) !important;
    }

    .container-fluid {
        max-width: 1200px;
    }

    .history-btn,
    .create-btn {
        min-height: 44px;
        transition: all 0.2s ease;
    }

    .history-btn {
        background: #fff;
    }

    .history-btn:hover {
        background: #f8fafc;
        border-color: #4f46e5 !important;
        color: #4f46e5 !important;
        transform: translateY(-1px);
    }

    .create-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 28px rgba(27, 0, 255, 0.18) !important;
    }

    .custom-table thead th {
        font-size: 12px;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 1px solid #e5e7eb;
        letter-spacing: 0.04em;
        font-weight: 700;
        white-space: nowrap;
    }

    .custom-table tbody tr {
        transition: all 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: #f8fafc;
    }

    .custom-table tbody td {
        padding-top: 14px;
        padding-bottom: 14px;
        border-color: #eef2f7;
    }

    .badge {
        font-size: 12px;
        font-weight: 600;
    }

    .audience-badge,
    .pinned-badge,
    .status-badge {
        min-height: 32px;
        display: inline-flex;
        align-items: center;
    }

    .action-btn {
        min-width: 38px;
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: translateY(-1px);
    }

    .btn-light:hover {
        background: #f1f5f9;
    }

    .alert-success {
        background: linear-gradient(135deg, #dcfce7, #f0fdf4);
        color: #166534;
    }

    .empty-state-icon {
        width: 72px;
        height: 72px;
        margin: 0 auto;
        border-radius: 50%;
        background: #f8fafc;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        border: 1px solid #e5e7eb;
    }
</style>
@endsection