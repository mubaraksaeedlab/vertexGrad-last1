@extends('layouts.app')

@section('title', __('backend.investment_requests.title'))

@section('content')
<style>
    .investment-requests-page .page-header-card {
        background: linear-gradient(135deg, #0d1b4c 0%, #1b00ff 100%);
        border-radius: 22px;
        padding: 30px 32px;
        color: #fff;
        box-shadow: 0 14px 34px rgba(27, 0, 255, 0.18);
        margin-bottom: 24px;
    }

    .investment-requests-page .page-header-card h3 {
        margin: 0;
        font-weight: 800;
        color: #fff;
        font-size: 30px;
    }

    .investment-requests-page .page-header-card p {
        margin: 10px 0 0;
        opacity: 0.92;
        font-size: 15px;
    }

    .investment-requests-page .stats-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2ff;
        height: 100%;
        transition: 0.25s ease;
    }

    .investment-requests-page .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.10);
    }

    .investment-requests-page .stats-icon {
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

    .investment-requests-page .stats-icon.primary { background: linear-gradient(135deg, #1b00ff, #4f46e5); }
    .investment-requests-page .stats-icon.warning { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .investment-requests-page .stats-icon.info { background: linear-gradient(135deg, #0891b2, #06b6d4); }
    .investment-requests-page .stats-icon.success { background: linear-gradient(135deg, #16a34a, #22c55e); }
    .investment-requests-page .stats-icon.danger { background: linear-gradient(135deg, #dc2626, #ef4444); }

    .investment-requests-page .stats-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 8px;
    }

    .investment-requests-page .stats-label {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0;
    }

    .investment-requests-page .table-card,
    .investment-requests-page .filter-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .investment-requests-page .table-card-header,
    .investment-requests-page .filter-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .investment-requests-page .table-card-header h5,
    .investment-requests-page .filter-card-header h5 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .investment-requests-page .modern-table {
        margin-bottom: 0;
        width: 100%;
        table-layout: auto;
    }

    .investment-requests-page .modern-table thead th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        padding: 13px 10px;
        vertical-align: middle;
        white-space: nowrap;
        font-size: 13px;
    }

    .investment-requests-page .modern-table tbody td {
        padding: 15px 10px;
        vertical-align: middle;
        border-color: #f1f5f9;
        font-size: 13px;
    }

    .investment-requests-page .modern-table tbody tr:hover {
        background: #fafcff;
    }

    .investment-requests-page .badge-soft {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .2px;
        white-space: nowrap;
    }

    .investment-requests-page .badge-interested {
        background: #fff7ed;
        color: #c2410c;
    }

    .investment-requests-page .badge-requested {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .investment-requests-page .badge-approved {
        background: #ecfdf5;
        color: #15803d;
    }

    .investment-requests-page .badge-rejected {
        background: #fef2f2;
        color: #dc2626;
    }

    .investment-requests-page .mini-text {
        font-size: 11px;
        color: #64748b;
        margin-top: 3px;
        line-height: 1.5;
    }

    .investment-requests-page .filter-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 6px;
    }

    .investment-requests-page .form-control,
    .investment-requests-page .form-select {
        border-radius: 12px;
        min-height: 42px;
        border: 1px solid #dbe4f0;
        box-shadow: none;
    }

    .investment-requests-page .btn-soft {
        border-radius: 12px;
        font-weight: 600;
        padding: 9px 14px;
        text-decoration: none;
        border: 1px solid #dbe4f0;
        background: #fff;
        color: #0f172a;
    }

    .investment-requests-page .btn-soft:hover {
        text-decoration: none;
        color: #0f172a;
        background: #f8fafc;
    }

    .investment-requests-page .btn-soft.active-filter {
        background: linear-gradient(135deg, #1b00ff, #4f46e5);
        color: #fff !important;
        border-color: transparent;
    }

    .investment-requests-page .action-buttons {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .investment-requests-page .icon-action {
        width: 34px;
        height: 34px;
        border: none;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.10);
    }

    .investment-requests-page .icon-action:hover {
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .investment-requests-page .icon-view { background: linear-gradient(135deg, #1b00ff, #4338ca); }
    .investment-requests-page .icon-project { background: linear-gradient(135deg, #0ea5e9, #2563eb); }
    .investment-requests-page .icon-requested { background: linear-gradient(135deg, #0891b2, #06b6d4); }
    .investment-requests-page .icon-interested { background: linear-gradient(135deg, #d97706, #f59e0b); }
    .investment-requests-page .icon-approve { background: linear-gradient(135deg, #16a34a, #22c55e); }
    .investment-requests-page .icon-reject { background: linear-gradient(135deg, #dc2626, #ef4444); }

    .investment-requests-page .empty-state {
        padding: 50px 20px;
        text-align: center;
        color: #64748b;
    }

    .investment-requests-page .empty-state i {
        font-size: 42px;
        margin-bottom: 12px;
        color: #cbd5e1;
    }

    .investment-requests-page .request-message {
        max-width: 260px;
        white-space: normal;
        word-break: break-word;
        color: #334155;
        line-height: 1.6;
    }

    .investment-requests-page .name-link {
        text-decoration: none;
        color: #0f172a;
        font-weight: 700;
    }

    .investment-requests-page .name-link:hover {
        color: #1b00ff;
        text-decoration: none;
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 investment-requests-page">
    <div class="min-height-200px">


        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 14px;">
                {{ session('error') }}
            </div>
        @endif

        <div class="page-header-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                <div>
                    <h3>{{ __('backend.investment_requests.page_title') }}</h3>
                    <p>{{ __('backend.investment_requests.page_subtitle') }}</p>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon primary"><i class="fa fa-list"></i></div>
                    <div class="stats-number">{{ $stats['total'] ?? 0 }}</div>
                    <p class="stats-label">{{ __('backend.investment_requests.total_requests') }}</p>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon warning"><i class="fa fa-star"></i></div>
                    <div class="stats-number">{{ $stats['interested'] ?? 0 }}</div>
                    <p class="stats-label">{{ __('backend.investment_requests.interested') }}</p>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon info"><i class="fa fa-clock"></i></div>
                    <div class="stats-number">{{ $stats['requested'] ?? 0 }}</div>
                    <p class="stats-label">{{ __('backend.investment_requests.requested') }}</p>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon success"><i class="fa fa-check"></i></div>
                    <div class="stats-number">{{ $stats['approved'] ?? 0 }}</div>
                    <p class="stats-label">{{ __('backend.investment_requests.approved') }}</p>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stats-card">
                    <div class="stats-icon danger"><i class="fa fa-times"></i></div>
                    <div class="stats-number">{{ $stats['rejected'] ?? 0 }}</div>
                    <p class="stats-label">{{ __('backend.investment_requests.rejected') }}</p>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-12 mb-3">
                <div class="stats-card">
                    <div class="stats-icon primary"><i class="fa fa-dollar-sign"></i></div>
                    <div class="stats-number">${{ number_format($stats['amount'] ?? 0, 2) }}</div>
                    <p class="stats-label">{{ __('backend.investment_requests.approved_amount') }}</p>
                </div>
            </div>
        </div>

        <div class="filter-card mb-4">
            <div class="filter-card-header">
                <div>
                    <h5>{{ __('backend.investment_requests.filters') }}</h5>
                    <small class="text-muted">{{ __('backend.investment_requests.filters_subtitle') }}</small>
                </div>

                <div class="d-flex flex-wrap" style="gap: 10px;">
                    <a href="{{ route('admin.investment-requests.index') }}"
                       class="btn-soft {{ request('status') === null ? 'active-filter' : '' }}">
                        {{ __('backend.investment_requests.all') }}
                    </a>

                    <a href="{{ route('admin.investment-requests.index', ['status' => 'interested']) }}"
                       class="btn-soft {{ request('status') === 'interested' ? 'active-filter' : '' }}">
                        {{ __('backend.investment_requests.interested') }}
                    </a>

                    <a href="{{ route('admin.investment-requests.index', ['status' => 'requested']) }}"
                       class="btn-soft {{ request('status') === 'requested' ? 'active-filter' : '' }}">
                        {{ __('backend.investment_requests.requested') }}
                    </a>

                    <a href="{{ route('admin.investment-requests.index', ['status' => 'approved']) }}"
                       class="btn-soft {{ request('status') === 'approved' ? 'active-filter' : '' }}">
                        {{ __('backend.investment_requests.approved') }}
                    </a>

                    <a href="{{ route('admin.investment-requests.index', ['status' => 'rejected']) }}"
                       class="btn-soft {{ request('status') === 'rejected' ? 'active-filter' : '' }}">
                        {{ __('backend.investment_requests.rejected') }}
                    </a>
                </div>
            </div>

            <div class="p-4">
                <form method="GET" action="{{ route('admin.investment-requests.index') }}">
                    <div class="row">
                        <div class="col-lg-5 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.investment_requests.search') }}</label>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="{{ __('backend.investment_requests.search_placeholder') }}"
                                   value="{{ request('search') }}">
                        </div>

                        <div class="col-lg-2 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.investment_requests.status') }}</label>
                            <select name="status" class="form-select">
                                <option value="">{{ __('backend.investment_requests.all_statuses') }}</option>
                                <option value="interested" {{ request('status') === 'interested' ? 'selected' : '' }}>{{ __('backend.investment_requests.interested') }}</option>
                                <option value="requested" {{ request('status') === 'requested' ? 'selected' : '' }}>{{ __('backend.investment_requests.requested') }}</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('backend.investment_requests.approved') }}</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('backend.investment_requests.rejected') }}</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.investment_requests.sort') }}</label>
                            <select name="sort_by" class="form-select">
                                <option value="latest" {{ request('sort_by', 'latest') === 'latest' ? 'selected' : '' }}>{{ __('backend.investment_requests.latest') }}</option>
                                <option value="oldest" {{ request('sort_by') === 'oldest' ? 'selected' : '' }}>{{ __('backend.investment_requests.oldest') }}</option>
                            </select>
                        </div>

                        <div class="col-lg-1 col-md-6 mb-3">
                            <label class="filter-label">{{ __('backend.investment_requests.per_page') }}</label>
                            <select name="per_page" class="form-select">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-12 mb-3 d-flex align-items-end">
                            <div class="w-100 d-flex" style="gap: 10px;">
                                <button type="submit" class="btn btn-primary w-100" style="border-radius: 12px; font-weight: 700;">
                                    <i class="fa fa-search mr-1"></i> {{ __('backend.investment_requests.filter') }}
                                </button>

                                <a href="{{ route('admin.investment-requests.index') }}"
                                   class="btn btn-light w-100"
                                   style="border-radius: 12px; font-weight: 700; border: 1px solid #dbe4f0;">
                                    {{ __('backend.investment_requests.reset') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-card">
            <div class="table-card-header">
                <div>
                    <h5>{{ __('backend.investment_requests.table_title') }}</h5>
                    <small class="text-muted">{{ __('backend.investment_requests.table_subtitle') }}</small>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('backend.investment_requests.investor') }}</th>
                            <th>{{ __('backend.investment_requests.project') }}</th>
                            <th>{{ __('backend.investment_requests.status') }}</th>
                            <th>{{ __('backend.investment_requests.amount') }}</th>
                            <th>{{ __('backend.investment_requests.message') }}</th>
                            <th>{{ __('backend.investment_requests.date') }}</th>
                            <th>{{ __('backend.investment_requests.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($investmentRequests as $item)
                            @php
                                $statusClass = match($item->status) {
                                    'interested' => 'badge-interested',
                                    'requested' => 'badge-requested',
                                    'approved' => 'badge-approved',
                                    'rejected' => 'badge-rejected',
                                    default => 'badge-requested',
                                };
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration + ($investmentRequests->currentPage() - 1) * $investmentRequests->perPage() }}</td>

                                <td>
                                    @if($item->investor)
                                        <a href="{{ route('admin.investors.show', $item->investor->id) }}" class="name-link">
                                            {{ $item->investor->name }}
                                        </a>
                                    @else
                                        <div style="font-weight:700; color:#1e293b;">—</div>
                                    @endif

                                    <div class="mini-text">{{ optional($item->investor)->email ?? '—' }}</div>
                                    <div class="mini-text">
                                        {{ __('backend.investment_requests.company') }}: {{ optional(optional($item->investor)->investor)->company ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    @if($item->project)
                                        <div class="name-link">
                                            {{ $item->project->name }}
                                        </div>
                                    @else
                                        <div class="name-link">—</div>
                                    @endif
                                    <div class="mini-text">
                                        {{ __('backend.investment_requests.category') }}: {{ optional($item->project)->category ?? '—' }}
                                    </div>
                                </td>

                                <td>
                                    <span class="badge-soft {{ $statusClass }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item->amount !== null ? '$' . number_format($item->amount, 2) : '—' }}
                                </td>

                                <td>
                                    <div class="request-message">
                                        {{ $item->message ?: '—' }}
                                    </div>
                                </td>

                                <td>
                                    <div>{{ optional($item->created_at)->format('Y-m-d') }}</div>
                                    <div class="mini-text">{{ optional($item->created_at)->format('h:i A') }}</div>
                                </td>

                                <td>
                                    <div class="action-buttons">
                                        @if($item->investor)
                                            <a href="{{ route('admin.investors.show', $item->investor->id) }}"
                                               class="icon-action icon-view"
                                               title="{{ __('backend.investment_requests.view_investor') }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endif

                                        @if($item->project)
                                            <a href="{{ route('admin.projects.show', $item->project->project_id) }}"
                                               class="icon-action icon-project"
                                               title="{{ __('backend.investment_requests.view_project') }}">
                                                <i class="fa fa-briefcase"></i>
                                            </a>
                                        @endif

                                        @if($item->status !== 'interested')
                                            <form action="{{ route('admin.investment-requests.update-status', $item->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="interested">
                                                <button type="submit"
                                                        class="icon-action icon-interested"
                                                        title="{{ __('backend.investment_requests.mark_as_interested') }}"
                                                        onclick="return confirm('{{ __('backend.investment_requests.confirm_interested') }}')">
                                                    <i class="fa fa-star"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($item->status !== 'requested')
                                            <form action="{{ route('admin.investment-requests.update-status', $item->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="requested">
                                                <button type="submit"
                                                        class="icon-action icon-requested"
                                                        title="{{ __('backend.investment_requests.mark_as_requested') }}"
                                                        onclick="return confirm('{{ __('backend.investment_requests.confirm_requested') }}')">
                                                    <i class="fa fa-clock"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($item->status !== 'approved')
                                            <form action="{{ route('admin.investment-requests.update-status', $item->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit"
                                                        class="icon-action icon-approve"
                                                        title="{{ __('backend.investment_requests.approve') }}"
                                                        onclick="return confirm('{{ __('backend.investment_requests.confirm_approve') }}')">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if($item->status !== 'rejected')
                                            <form action="{{ route('admin.investment-requests.update-status', $item->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit"
                                                        class="icon-action icon-reject"
                                                        title="{{ __('backend.investment_requests.reject') }}"
                                                        onclick="return confirm('{{ __('backend.investment_requests.confirm_reject') }}')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <i class="fa fa-hand-holding-usd"></i>
                                        <div>{{ __('backend.investment_requests.no_investment_requests_found') }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $investmentRequests->links() }}
            </div>
        </div>

    </div>
</div>
@endsection