@extends('layouts.app')

@section('title', __('backend.investor_reports.title'))

@section('content')
<style>
    .investor-reports-page .page-header-card {
        background: linear-gradient(135deg, #0d1b4c 0%, #1b00ff 100%);
        border-radius: 22px;
        padding: 30px 32px;
        color: #fff;
        box-shadow: 0 14px 34px rgba(27, 0, 255, 0.18);
        margin-bottom: 24px;
    }

    .investor-reports-page .stats-card,
    .investor-reports-page .section-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        height: 100%;
    }

    .investor-reports-page .stats-card {
        padding: 22px;
    }

    .investor-reports-page .stats-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 8px;
    }

    .investor-reports-page .stats-label {
        color: #64748b;
        font-weight: 600;
        margin-bottom: 0;
    }

    .investor-reports-page .section-header {
        padding: 18px 22px;
        border-bottom: 1px solid #eef2f7;
        font-weight: 700;
        color: #0f172a;
    }

    .investor-reports-page .section-body {
        padding: 22px;
    }

    .investor-reports-page .list-clean {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .investor-reports-page .list-clean li {
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .investor-reports-page .list-clean li:last-child {
        border-bottom: none;
    }

    .investor-reports-page .record-title {
        font-weight: 700;
        color: #0f172a;
    }

    .investor-reports-page .record-meta {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
    }

    .investor-reports-page .btn-export {
        background: #fff;
        border: 1px solid #dbe4f0;
        color: #0f172a;
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        text-decoration: none;
    }

    .investor-reports-page .btn-export:hover {
        text-decoration: none;
        color: #0f172a;
        background: #f8fafc;
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 investor-reports-page">
    <div class="min-height-200px">

        <div class="page-header-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                <div>
                    <h3 class="mb-1">{{ __('backend.investor_reports.page_title') }}</h3>
                    <p class="mb-0">{{ __('backend.investor_reports.page_subtitle') }}</p>
                </div>

                <a href="{{ route('admin.investor-reports.export') }}" class="btn-export">
                    <i class="fa fa-file-excel mr-1"></i> {{ __('backend.investor_reports.export_report') }}
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3"><div class="stats-card"><div class="stats-number">{{ $stats['total_investors'] }}</div><p class="stats-label">{{ __('backend.investor_reports.total_investors') }}</p></div></div>
            <div class="col-xl-3 col-md-6 mb-3"><div class="stats-card"><div class="stats-number">{{ $stats['active_investors'] }}</div><p class="stats-label">{{ __('backend.investor_reports.active_investors') }}</p></div></div>
            <div class="col-xl-3 col-md-6 mb-3"><div class="stats-card"><div class="stats-number">{{ $stats['inactive_investors'] }}</div><p class="stats-label">{{ __('backend.investor_reports.inactive_investors') }}</p></div></div>
            <div class="col-xl-3 col-md-6 mb-3"><div class="stats-card"><div class="stats-number">{{ $stats['archived_investors'] }}</div><p class="stats-label">{{ __('backend.investor_reports.archived_investors') }}</p></div></div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-2 col-md-4 col-6 mb-3"><div class="stats-card"><div class="stats-number">{{ $stats['total_requests'] }}</div><p class="stats-label">{{ __('backend.investor_reports.total_requests') }}</p></div></div>
            <div class="col-xl-2 col-md-4 col-6 mb-3"><div class="stats-card"><div class="stats-number">{{ $stats['interested'] }}</div><p class="stats-label">{{ __('backend.investor_reports.interested') }}</p></div></div>
            <div class="col-xl-2 col-md-4 col-6 mb-3"><div class="stats-card"><div class="stats-number">{{ $stats['requested'] }}</div><p class="stats-label">{{ __('backend.investor_reports.requested') }}</p></div></div>
            <div class="col-xl-2 col-md-4 col-6 mb-3"><div class="stats-card"><div class="stats-number">{{ $stats['approved'] }}</div><p class="stats-label">{{ __('backend.investor_reports.approved') }}</p></div></div>
            <div class="col-xl-2 col-md-4 col-6 mb-3"><div class="stats-card"><div class="stats-number">{{ $stats['rejected'] }}</div><p class="stats-label">{{ __('backend.investor_reports.rejected') }}</p></div></div>
            <div class="col-xl-2 col-md-4 col-12 mb-3"><div class="stats-card"><div class="stats-number">${{ number_format($stats['approved_amount'], 2) }}</div><p class="stats-label">{{ __('backend.investor_reports.approved_amount') }}</p></div></div>
        </div>

        <div class="row">
            <div class="col-xl-4 mb-4">
                <div class="section-card">
                    <div class="section-header">{{ __('backend.investor_reports.top_investors_by_approved_amount') }}</div>
                    <div class="section-body">
                        @if($topApprovedInvestors->count())
                            <ul class="list-clean">
                                @foreach($topApprovedInvestors as $row)
                                    <li>
                                        <div class="record-title">{{ optional($row->investor)->name ?? __('backend.investor_reports.unknown_investor') }}</div>
                                        <div class="record-meta">
                                            {{ __('backend.investor_reports.approved_requests') }}: {{ $row->approved_count }} |
                                            {{ __('backend.investor_reports.approved_amount') }}: ${{ number_format($row->approved_amount, 2) }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">{{ __('backend.investor_reports.no_approved_investment_data_yet') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="section-card">
                    <div class="section-header">{{ __('backend.investor_reports.top_investors_by_total_requests') }}</div>
                    <div class="section-body">
                        @if($topRequestInvestors->count())
                            <ul class="list-clean">
                                @foreach($topRequestInvestors as $row)
                                    <li>
                                        <div class="record-title">{{ optional($row->investor)->name ?? __('backend.investor_reports.unknown_investor') }}</div>
                                        <div class="record-meta">
                                            {{ __('backend.investor_reports.total_requests') }}: {{ $row->total_requests }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">{{ __('backend.investor_reports.no_request_data_yet') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="section-card">
                    <div class="section-header">{{ __('backend.investor_reports.latest_investor_activities') }}</div>
                    <div class="section-body">
                        @if($latestActivities->count())
                            <ul class="list-clean">
                                @foreach($latestActivities as $activity)
                                    <li>
                                        <div class="record-title">
                                            {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                        </div>
                                        <div class="record-meta">
                                            {{ __('backend.investor_reports.investor') }}: {{ optional(optional($activity->investor)->user)->name ?? __('backend.investor_reports.unknown') }} |
                                            {{ __('backend.investor_reports.by') }}: {{ optional($activity->user)->name ?? __('backend.investor_reports.system') }}
                                        </div>
                                        <div class="record-meta">
                                            {{ optional($activity->created_at)->format('Y-m-d h:i A') }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">{{ __('backend.investor_reports.no_investor_activity_found') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection