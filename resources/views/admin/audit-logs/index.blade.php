@extends('layouts.app')

@section('title', __('backend.audit_center.title'))

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="audit-center-page container-fluid py-4 px-lg-3 px-xl-4">

    {{-- Header --}}
    <div class="audit-hero-card mb-4">
        <div class="audit-hero-content">
            <div>
                <div class="audit-eyebrow">{{ __('backend.audit_center.system_monitoring') }}</div>
                <h2 class="audit-page-title mb-2">{{ __('backend.audit_center.page_title') }}</h2>
                <p class="audit-page-subtitle mb-0">
                    {{ __('backend.audit_center.page_subtitle') }}
                </p>
            </div>

            <div class="audit-hero-right">
                <div class="audit-hero-meta">
                    <div class="audit-meta-box">
                        <span class="audit-meta-label">{{ __('backend.audit_center.records') }}</span>
                        <strong>{{ number_format($analytics['total'] ?? 0) }}</strong>
                    </div>
                    <div class="audit-meta-box">
                        <span class="audit-meta-label">{{ __('backend.audit_center.today') }}</span>
                        <strong>{{ number_format($analytics['today'] ?? 0) }}</strong>
                    </div>
                </div>

                <div class="audit-export-actions">
                    <a href="{{ route('admin.audit.export.excel', request()->query()) }}" class="btn audit-export-btn audit-export-excel">
                        <i class="bi bi-file-earmark-excel me-1"></i>
                        {{ __('backend.audit_center.export_excel') }}
                    </a>

                    <a href="{{ route('admin.audit.export.pdf', request()->query()) }}" class="btn audit-export-btn audit-export-pdf">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        {{ __('backend.audit_center.export_pdf') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="audit-hero-pattern"></div>
    </div>

    {{-- Analytics --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl">
            <div class="audit-stat-card">
                <div class="audit-stat-icon stat-total">
                    <i class="bi bi-collection"></i>
                </div>
                <div>
                    <div class="audit-stat-label">{{ __('backend.audit_center.total_records') }}</div>
                    <div class="audit-stat-value">{{ number_format($analytics['total'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="audit-stat-card">
                <div class="audit-stat-icon stat-today">
                    <i class="bi bi-calendar-day"></i>
                </div>
                <div>
                    <div class="audit-stat-label">{{ __('backend.audit_center.today') }}</div>
                    <div class="audit-stat-value">{{ number_format($analytics['today'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="audit-stat-card">
                <div class="audit-stat-icon stat-created">
                    <i class="bi bi-plus-circle"></i>
                </div>
                <div>
                    <div class="audit-stat-label">{{ __('backend.audit_center.created') }}</div>
                    <div class="audit-stat-value">{{ number_format($analytics['created'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="audit-stat-card">
                <div class="audit-stat-icon stat-updated">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <div class="audit-stat-label">{{ __('backend.audit_center.updated') }}</div>
                    <div class="audit-stat-value">{{ number_format($analytics['updated'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl">
            <div class="audit-stat-card">
                <div class="audit-stat-icon stat-deleted">
                    <i class="bi bi-trash3"></i>
                </div>
                <div>
                    <div class="audit-stat-label">{{ __('backend.audit_center.deleted') }}</div>
                    <div class="audit-stat-value">{{ number_format($analytics['deleted'] ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="audit-filter-card mb-4">
        <div class="audit-section-head mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1">{{ __('backend.audit_center.filters') }}</h5>
                <p class="mb-0">{{ __('backend.audit_center.filters_subtitle') }}</p>
            </div>

            <button
                class="btn audit-collapse-btn"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#advancedAuditFilters"
                aria-expanded="{{ request()->hasAny(['user','category','event','from','to']) ? 'true' : 'false' }}"
                aria-controls="advancedAuditFilters"
            >
                <i class="bi bi-sliders me-1"></i> {{ __('backend.audit_center.advanced_filters') }}
            </button>
        </div>

        <form method="GET" action="{{ url()->current() }}">
            <div class="row g-3 align-items-end">
                <div class="col-lg-8">
                    <label class="audit-label">{{ __('backend.audit_center.quick_search') }}</label>
                    <div class="audit-input-icon">
                        <i class="bi bi-search"></i>
                        <input
                            type="text"
                            name="search"
                            class="form-control audit-control"
                            placeholder="{{ __('backend.audit_center.quick_search_placeholder') }}"
                            value="{{ request('search') }}"
                        >
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <button type="submit" class="btn audit-btn-primary">
                            <i class="bi bi-funnel me-1"></i> {{ __('backend.audit_center.apply_filters') }}
                        </button>

                        <a href="{{ url()->current() }}" class="btn audit-btn-light">
                            <i class="bi bi-arrow-clockwise me-1"></i> {{ __('backend.audit_center.reset') }}
                        </a>
                    </div>
                </div>
            </div>

            <div class="collapse {{ request()->hasAny(['user','category','event','from','to']) ? 'show' : '' }}" id="advancedAuditFilters">
                <div class="audit-advanced-wrap mt-4">
                    <div class="row g-3">
                        <div class="col-md-6 col-xl-3">
                            <label class="audit-label">{{ __('backend.audit_center.user') }}</label>
                            <input
                                type="text"
                                name="user"
                                class="form-control audit-control"
                                placeholder="{{ __('backend.audit_center.user_placeholder') }}"
                                value="{{ request('user') }}"
                            >
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <label class="audit-label">{{ __('backend.audit_center.category') }}</label>
                            <input
                                type="text"
                                name="category"
                                class="form-control audit-control"
                                placeholder="{{ __('backend.audit_center.category_placeholder') }}"
                                value="{{ request('category') }}"
                            >
                        </div>

                        <div class="col-md-6 col-xl-2">
                            <label class="audit-label">{{ __('backend.audit_center.event') }}</label>
                            <select name="event" class="form-select audit-control">
                                <option value="">{{ __('backend.audit_center.all_events') }}</option>
                                <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>{{ __('backend.audit_center.created') }}</option>
                                <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>{{ __('backend.audit_center.updated') }}</option>
                                <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>{{ __('backend.audit_center.deleted') }}</option>
                                <option value="approved" {{ request('event') === 'approved' ? 'selected' : '' }}>{{ __('backend.audit_center.approved') }}</option>
                                <option value="rejected" {{ request('event') === 'rejected' ? 'selected' : '' }}>{{ __('backend.audit_center.rejected') }}</option>
                                <option value="login" {{ request('event') === 'login' ? 'selected' : '' }}>{{ __('backend.audit_center.login') }}</option>
                                <option value="logout" {{ request('event') === 'logout' ? 'selected' : '' }}>{{ __('backend.audit_center.logout') }}</option>
                                <option value="exported" {{ request('event') === 'exported' ? 'selected' : '' }}>{{ __('backend.audit_center.exported') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6 col-xl-2">
                            <label class="audit-label">{{ __('backend.audit_center.from_date') }}</label>
                            <input
                                type="date"
                                name="from"
                                class="form-control audit-control"
                                value="{{ request('from') }}"
                            >
                        </div>

                        <div class="col-md-6 col-xl-2">
                            <label class="audit-label">{{ __('backend.audit_center.to_date') }}</label>
                            <input
                                type="date"
                                name="to"
                                class="form-control audit-control"
                                value="{{ request('to') }}"
                            >
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="audit-table-card">
        <div class="audit-section-head audit-table-head">
            <div>
                <h5 class="mb-1">{{ __('backend.audit_center.activity_records') }}</h5>
                <p class="mb-0">{{ __('backend.audit_center.activity_records_subtitle') }}</p>
            </div>

            <div class="audit-table-count">
                {{ $logs->total() ?? 0 }} {{ __('backend.audit_center.records_count') }}
            </div>
        </div>

        <div class="audit-table-wrap">
            <table class="table audit-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 18%;">{{ __('backend.audit_center.actor') }}</th>
                        <th style="width: 10%;">{{ __('backend.audit_center.event') }}</th>
                        <th style="width: 12%;">{{ __('backend.audit_center.category') }}</th>
                        <th style="width: 15%;">{{ __('backend.audit_center.subject') }}</th>
                        <th style="width: 27%;">{{ __('backend.audit_center.description') }}</th>
                        <th style="width: 10%;">{{ __('backend.audit_center.date') }}</th>
                        <th style="width: 8%;" class="text-center">{{ __('backend.audit_center.details') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($logs as $log)
                        @php
                            $event = strtolower($log->event ?? '');

                            $eventClass = match($event) {
                                'created' => 'badge-created',
                                'updated' => 'badge-updated',
                                'deleted' => 'badge-deleted',
                                'approved' => 'badge-approved',
                                'rejected' => 'badge-rejected',
                                'login' => 'badge-login',
                                'logout' => 'badge-logout',
                                'exported' => 'badge-exported',
                                default => 'badge-default',
                            };

                            $categoryText = $log->category ? ucwords(str_replace(['_', '-'], ' ', $log->category)) : __('backend.audit_center.general');
                        @endphp

                        <tr>
                            <td>
                                <div class="audit-user-cell">
                                    <div class="audit-user-avatar">
                                        {{ strtoupper(mb_substr($log->user_name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="audit-user-name">
                                            {{ $log->user_name ?? __('backend.audit_center.system') }}
                                        </div>
                                        <div class="audit-user-role">
                                            {{ $log->user_type ?? __('backend.audit_center.system') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="audit-event-badge {{ $eventClass }}">
                                    {{ $log->event_label }}
                                </span>
                            </td>

                            <td>
                                <span class="audit-category-badge">
                                    {{ $categoryText }}
                                </span>
                            </td>

                            <td>
                                <div class="audit-subject-title">
                                    {{ $log->subject_title ?? '—' }}
                                </div>
                                @if(!empty($log->subject_type))
                                    <div class="audit-subject-type">
                                        {{ class_basename($log->subject_type) }}
                                        @if(!empty($log->subject_id))
                                            • #{{ $log->subject_id }}
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td>
                                <div class="audit-description">
                                    {{ Str::limit($log->description, 110) }}
                                </div>
                            </td>

                            <td>
                                <div class="audit-date-stack">
                                    <div class="audit-date-main">
                                        {{ $log->created_at?->format('M d, Y') }}
                                    </div>
                                    <div class="audit-date-divider"></div>
                                    <div class="audit-date-sub">
                                        {{ $log->created_at?->format('h:i A') }}
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <button
                                    type="button"
                                    class="btn audit-details-btn"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#auditDetails{{ $log->id }}"
                                    aria-expanded="false"
                                    aria-controls="auditDetails{{ $log->id }}"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>
                            </td>
                        </tr>

                        <tr class="audit-details-row">
                            <td colspan="7" class="p-0 border-0">
                                <div class="collapse" id="auditDetails{{ $log->id }}">
                                    <div class="audit-details-panel">
                                        <div class="row g-3">
                                            <div class="col-lg-3">
                                                <div class="audit-detail-card">
                                                    <div class="audit-detail-title">{{ __('backend.audit_center.actor_information') }}</div>
                                                    <ul class="audit-detail-list">
                                                        <li><strong>{{ __('backend.audit_center.user_id') }}</strong> {{ $log->user_id ?? '—' }}</li>
                                                        <li><strong>{{ __('backend.audit_center.user_name') }}</strong> {{ $log->user_name ?? __('backend.audit_center.system') }}</li>
                                                        <li><strong>{{ __('backend.audit_center.user_type') }}</strong> {{ $log->user_type ?? __('backend.audit_center.system') }}</li>
                                                        <li><strong>{{ __('backend.audit_center.ip_address') }}</strong> {{ $log->ip_address ?? '—' }}</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-lg-3">
                                                <div class="audit-detail-card">
                                                    <div class="audit-detail-title">{{ __('backend.audit_center.subject_details') }}</div>
                                                    <ul class="audit-detail-list">
                                                        <li><strong>{{ __('backend.audit_center.subject_title') }}</strong> {{ $log->subject_title ?? '—' }}</li>
                                                        <li><strong>{{ __('backend.audit_center.subject_type') }}</strong> {{ $log->subject_type ?? '—' }}</li>
                                                        <li><strong>{{ __('backend.audit_center.subject_id') }}</strong> {{ $log->subject_id ?? '—' }}</li>
                                                        <li><strong>{{ __('backend.audit_center.created_at') }}</strong> {{ $log->created_at?->format('Y-m-d h:i:s A') }}</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="audit-detail-card">
                                                    <div class="audit-detail-title">{{ __('backend.audit_center.user_agent') }}</div>
                                                    <div class="audit-detail-pre simple-pre">
                                                        {{ $log->user_agent ?? '—' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="audit-detail-card">
                                                    <div class="audit-detail-title">{{ __('backend.audit_center.old_values') }}</div>
                                                    <pre class="audit-detail-pre">{{ !empty($log->old_values) ? json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : __('backend.audit_center.no_old_values') }}</pre>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="audit-detail-card">
                                                    <div class="audit-detail-title">{{ __('backend.audit_center.new_values') }}</div>
                                                    <pre class="audit-detail-pre">{{ !empty($log->new_values) ? json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : __('backend.audit_center.no_new_values') }}</pre>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="audit-detail-card">
                                                    <div class="audit-detail-title">{{ __('backend.audit_center.properties') }}</div>
                                                    <pre class="audit-detail-pre">{{ !empty($log->properties) ? json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : __('backend.audit_center.no_properties') }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="audit-empty-state">
                                    <div class="audit-empty-icon">
                                        <i class="bi bi-inboxes"></i>
                                    </div>
                                    <h5>{{ __('backend.audit_center.no_audit_records_found') }}</h5>
                                    <p>
                                        {{ __('backend.audit_center.no_audit_records_subtitle') }}
                                    </p>
                                    <div class="mt-3">
                                        <a href="{{ url()->current() }}" class="btn audit-btn-light">
                                            <i class="bi bi-arrow-clockwise me-1"></i> {{ __('backend.audit_center.reset_filters') }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($logs, 'hasPages') && $logs->hasPages())
            <div class="audit-pagination-wrap">
                <div class="custom-pagination">
                    @if ($logs->onFirstPage())
                        <span class="page-box disabled">‹</span>
                    @else
                        <a href="{{ $logs->appends(request()->query())->previousPageUrl() }}" class="page-box">‹</a>
                    @endif

                    @php
                        $current = $logs->currentPage();
                        $last = $logs->lastPage();

                        $start = max($current - 1, 1);
                        $end = min($current + 1, $last);

                        if ($current <= 2) {
                            $end = min(3, $last);
                        }

                        if ($current >= $last - 1) {
                            $start = max($last - 2, 1);
                        }
                    @endphp

                    @if ($start > 1)
                        <a href="{{ $logs->appends(request()->query())->url(1) }}" class="page-box">1</a>

                        @if ($start > 2)
                            <span class="page-box dots">...</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $current)
                            <span class="page-box active">{{ $page }}</span>
                        @else
                            <a href="{{ $logs->appends(request()->query())->url($page) }}" class="page-box">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="page-box dots">...</span>
                        @endif

                        <a href="{{ $logs->appends(request()->query())->url($last) }}" class="page-box">{{ $last }}</a>
                    @endif

                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->appends(request()->query())->nextPageUrl() }}" class="page-box">›</a>
                    @else
                        <span class="page-box disabled">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.audit-center-page {
    background: #f8fafc;
    min-height: calc(100vh - 90px);
}

.audit-hero-card {
    position: relative;
    overflow: hidden;
    border-radius: 26px;
    background: linear-gradient(135deg, #0f172a 0%, #16213e 45%, #1d4ed8 100%);
    padding: 30px;
    box-shadow: 0 20px 45px rgba(15, 23, 42, 0.18);
}

.audit-hero-content {
    position: relative;
    z-index: 2;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 18px;
    flex-wrap: wrap;
}

.audit-hero-right {
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: flex-end;
}

.audit-export-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    justify-content: flex-end;
}

.audit-export-btn {
    min-height: 42px;
    border-radius: 14px;
    font-weight: 700;
    font-size: 13px;
    padding: 9px 14px;
    border: none;
}

.audit-export-excel {
    background: #16a34a;
    color: #fff;
    box-shadow: 0 10px 18px rgba(22, 163, 74, 0.18);
}

.audit-export-excel:hover {
    color: #fff;
    background: #15803d;
}

.audit-export-pdf {
    background: #dc2626;
    color: #fff;
    box-shadow: 0 10px 18px rgba(220, 38, 38, 0.18);
}

.audit-export-pdf:hover {
    color: #fff;
    background: #b91c1c;
}

.audit-hero-pattern {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(circle at top right, rgba(255,255,255,0.18), transparent 22%),
        radial-gradient(circle at bottom left, rgba(255,255,255,0.10), transparent 24%);
    z-index: 1;
}

.audit-eyebrow {
    color: rgba(255,255,255,0.72);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.4px;
    margin-bottom: 10px;
}

.audit-page-title {
    color: #fff;
    font-size: 32px;
    font-weight: 800;
    letter-spacing: -0.5px;
}

.audit-page-subtitle {
    color: rgba(255,255,255,0.82);
    max-width: 720px;
    font-size: 14px;
    line-height: 1.7;
}

.audit-hero-meta {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.audit-meta-box {
    min-width: 120px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.16);
    backdrop-filter: blur(10px);
    border-radius: 18px;
    padding: 14px 16px;
    color: #fff;
}

.audit-meta-label {
    display: block;
    font-size: 11px;
    color: rgba(255,255,255,0.72);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

.audit-meta-box strong {
    font-size: 22px;
    font-weight: 800;
}

.audit-stat-card,
.audit-filter-card,
.audit-table-card {
    background: #ffffff;
    border: 1px solid #e9eef5;
    border-radius: 22px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
}

.audit-stat-card {
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    height: 100%;
    transition: all 0.2s ease;
}

.audit-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 32px rgba(15, 23, 42, 0.08);
}

.audit-stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.stat-total {
    background: rgba(37, 99, 235, 0.10);
    color: #2563eb;
}

.stat-today {
    background: rgba(14, 165, 233, 0.10);
    color: #0ea5e9;
}

.stat-created {
    background: rgba(34, 197, 94, 0.10);
    color: #16a34a;
}

.stat-updated {
    background: rgba(245, 158, 11, 0.12);
    color: #d97706;
}

.stat-deleted {
    background: rgba(239, 68, 68, 0.10);
    color: #dc2626;
}

.audit-stat-label {
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 5px;
}

.audit-stat-value {
    color: #0f172a;
    font-size: 28px;
    font-weight: 800;
    line-height: 1;
}

.audit-filter-card {
    padding: 22px;
}

.audit-advanced-wrap {
    border-top: 1px dashed #dbe4ee;
    padding-top: 18px;
}

.audit-section-head h5 {
    color: #0f172a;
    font-size: 18px;
    font-weight: 800;
}

.audit-section-head p {
    color: #64748b;
    font-size: 13px;
}

.audit-label {
    display: block;
    margin-bottom: 8px;
    color: #334155;
    font-weight: 700;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}

.audit-control {
    min-height: 48px;
    border-radius: 14px;
    border: 1px solid #dbe4ee;
    background: #fff;
    box-shadow: none !important;
    font-size: 14px;
}

.audit-control:focus {
    border-color: #3b82f6;
}

.audit-input-icon {
    position: relative;
}

.audit-input-icon i {
    position: absolute;
    top: 50%;
    left: 14px;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 14px;
}

.audit-input-icon .audit-control {
    padding-left: 40px;
}

.audit-btn-primary {
    min-height: 48px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
    color: #fff;
    font-weight: 700;
    box-shadow: 0 10px 20px rgba(37, 99, 235, 0.20);
}

.audit-btn-primary:hover {
    color: #fff;
    opacity: 0.96;
}

.audit-btn-light,
.audit-collapse-btn {
    min-height: 46px;
    border-radius: 14px;
    border: 1px solid #dbe4ee;
    background: #fff;
    color: #334155;
    font-weight: 700;
}

.audit-collapse-btn:hover,
.audit-btn-light:hover {
    background: #f8fafc;
    color: #0f172a;
}

.audit-table-card {
    overflow: hidden;
}

.audit-table-head {
    padding: 22px 22px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.audit-table-count {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #dbeafe;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}

.audit-table-wrap {
    padding: 10px 0 0;
}

.audit-table {
    width: 100%;
    table-layout: fixed;
}

.audit-table thead th {
    border-bottom: 1px solid #eef2f7;
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.9px;
    padding: 16px 20px;
    background: #fcfdff;
}

.audit-table tbody td {
    padding: 18px 20px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}

.audit-table tbody tr:hover {
    background: #fbfdff;
}

.audit-user-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.audit-user-avatar {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 15px;
    flex-shrink: 0;
    box-shadow: 0 8px 18px rgba(37, 99, 235, 0.18);
}

.audit-user-name {
    color: #0f172a;
    font-weight: 800;
    font-size: 14px;
    line-height: 1.2;
}

.audit-user-role {
    color: #64748b;
    font-size: 12px;
    margin-top: 3px;
}

.audit-event-badge,
.audit-category-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 999px;
    padding: 7px 12px;
    font-size: 12px;
    font-weight: 700;
    white-space: normal;
    text-align: center;
    line-height: 1.3;
    max-width: 500px;
}

.audit-category-badge {
    background: #f8fafc;
    color: #334155;
    border: 1px solid #e2e8f0;
}

.badge-created {
    background: rgba(34, 197, 94, 0.12);
    color: #15803d;
    border: 1px solid rgba(34, 197, 94, 0.18);
}

.badge-updated {
    background: rgba(245, 158, 11, 0.14);
    color: #b45309;
    border: 1px solid rgba(245, 158, 11, 0.20);
}

.badge-deleted {
    background: rgba(239, 68, 68, 0.12);
    color: #b91c1c;
    border: 1px solid rgba(239, 68, 68, 0.18);
}

.badge-approved {
    background: rgba(16, 185, 129, 0.12);
    color: #047857;
    border: 1px solid rgba(16, 185, 129, 0.18);
}

.badge-rejected {
    background: rgba(244, 63, 94, 0.12);
    color: #be123c;
    border: 1px solid rgba(244, 63, 94, 0.18);
}

.badge-login {
    background: rgba(59, 130, 246, 0.12);
    color: #1d4ed8;
    border: 1px solid rgba(59, 130, 246, 0.18);
}

.badge-logout {
    background: rgba(107, 114, 128, 0.12);
    color: #374151;
    border: 1px solid rgba(107, 114, 128, 0.18);
}

.badge-exported {
    background: rgba(139, 92, 246, 0.12);
    color: #6d28d9;
    border: 1px solid rgba(139, 92, 246, 0.18);
}

.badge-default {
    background: rgba(148, 163, 184, 0.12);
    color: #475569;
    border: 1px solid rgba(148, 163, 184, 0.18);
}

.audit-description {
    color: #0f172a;
    font-size: 13px;
    font-weight: 600;
    line-height: 1.7;
}

.audit-subject-title {
    color: #0f172a;
    font-size: 14px;
    font-weight: 700;
    line-height: 1.4;
}

.audit-subject-type {
    margin-top: 4px;
    font-size: 12px;
    color: #64748b;
}

.audit-date-stack {
    display: inline-flex;
    flex-direction: column;
    gap: 6px;
    min-width: 118px;
    padding: 10px 12px;
    border-radius: 14px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.audit-date-main {
    color: #0f172a;
    font-size: 13px;
    font-weight: 800;
    line-height: 1.2;
}

.audit-date-divider {
    width: 100%;
    height: 1px;
    background: #e2e8f0;
}

.audit-date-sub {
    color: #64748b;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
}

.audit-details-btn {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    border: 1px solid #dbe4ee;
    background: #fff;
    color: #334155;
    font-weight: 700;
    font-size: 13px;
    padding: 5;
    margin-left: 10px;
}

.audit-details-btn:hover {
    background: #f8fafc;
    color: #0f172a;
}

.audit-details-row td {
    background: #ffffff !important;
}

.audit-details-panel {
    padding: 0 20px 22px 20px;
    background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
    border-top: 1px dashed #dbe4ee;
}

.audit-detail-card {
    height: 100%;
    background: #fff;
    border: 1px solid #e9eef5;
    border-radius: 18px;
    padding: 16px;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
}

.audit-detail-title {
    color: #0f172a;
    font-size: 13px;
    font-weight: 800;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}

.audit-detail-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.audit-detail-list li {
    color: #334155;
    font-size: 13px;
    margin-bottom: 8px;
    line-height: 1.6;
    word-break: break-word;
}

.audit-detail-pre {
    background: #0f172a;
    color: #e2e8f0;
    border-radius: 14px;
    padding: 14px;
    font-size: 11px;
    line-height: 1.55;
    white-space: pre-wrap;
    word-break: break-word;
    margin: 0;
    min-height: 180px;
    overflow: hidden;
}

.simple-pre {
    min-height: auto;
    background: #f8fafc;
    color: #334155;
    border: 1px solid #e2e8f0;
}

.audit-empty-state {
    text-align: center;
    padding: 60px 20px;
}

.audit-empty-icon {
    width: 70px;
    height: 70px;
    margin: 0 auto 18px;
    border-radius: 22px;
    background: #eff6ff;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 30px;
}

.audit-empty-state h5 {
    color: #0f172a;
    font-weight: 800;
    margin-bottom: 8px;
}

.audit-empty-state p {
    color: #64748b;
    max-width: 480px;
    margin: 0 auto;
    line-height: 1.7;
}

.audit-pagination-wrap {
    padding: 20px 22px;
    border-top: 1px solid #eef2f7;
    background: #fff;
}

.custom-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}

.custom-pagination .page-box {
    min-width: 44px;
    height: 44px;
    padding: 0 14px;
    border-radius: 12px;
    border: 1px solid #e3e8f2;
    background: #ffffff;
    color: #172033;
    font-weight: 700;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
    box-shadow: 0 4px 12px rgba(18, 38, 63, 0.05);
}

.custom-pagination .page-box:hover {
    background: #4e73df;
    color: #ffffff;
    border-color: #4e73df;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(78, 115, 223, 0.18);
}

.custom-pagination .page-box.active {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: #ffffff;
    border-color: transparent;
    box-shadow: 0 10px 22px rgba(78, 115, 223, 0.28);
}

.custom-pagination .page-box.disabled {
    opacity: 0.45;
    pointer-events: none;
    background: #f5f7fb;
    color: #98a2b3;
    box-shadow: none;
}

.custom-pagination .page-box.dots {
    border-style: dashed;
    background: #f9fbff;
    color: #7b8497;
    pointer-events: none;
}

@media (max-width: 1399.98px) {
    .audit-table thead th,
    .audit-table tbody td {
        padding-left: 14px;
        padding-right: 14px;
    }

    .audit-description {
        font-size: 11px;
    }
}

@media (max-width: 991.98px) {
    .audit-hero-card {
        padding: 24px;
    }

    .audit-page-title {
        font-size: 26px;
    }

    .audit-hero-right {
        width: 100%;
        align-items: stretch;
    }

    .audit-export-actions {
        justify-content: flex-start;
    }

    .audit-table {
        table-layout: auto;
    }

    .audit-date-stack {
        min-width: auto;
    }
}

@media (max-width: 576px) {
    .custom-pagination {
        gap: 8px;
    }

    .custom-pagination .page-box {
        min-width: 40px;
        height: 40px;
        border-radius: 10px;
        font-size: 0.88rem;
        padding: 0 10px;
    }
}
</style>
@endsection