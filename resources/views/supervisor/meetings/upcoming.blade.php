@extends('supervisor.layout.app_super')

@section('title', __('backend.supervisor_upcoming_meetings.page_title'))

@section('content')
@php
    $totalMeetings = method_exists($meetings, 'total') ? $meetings->total() : $meetings->count();

    $todayMeetings = collect(method_exists($meetings, 'items') ? $meetings->items() : $meetings)
        ->filter(fn($m) => $m->meeting_date == now()->format('Y-m-d'))
        ->count();

    $demoCount = collect(method_exists($meetings, 'items') ? $meetings->items() : $meetings)
        ->where('meeting_type', 'demo')
        ->count();
@endphp

<style>
    .meetings-page .page-header-card {
        background: linear-gradient(135deg, #0d1b4c 0%, #1b00ff 100%);
        border-radius: 20px;
        padding: 28px 30px;
        color: #fff;
        box-shadow: 0 12px 30px rgba(27, 0, 255, 0.18);
    }

    .meetings-page .stats-card {
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border: 1px solid #eef2ff;
        transition: 0.3s;
    }

    .meetings-page .stats-number {
        font-size: 26px;
        font-weight: 800;
    }

    .meetings-page .meeting-card {
        background: #fff;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid #edf2f7;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        transition: 0.3s;
    }

    .meetings-page .meeting-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(15, 23, 42, 0.08);
    }

    .meetings-page .badge-soft {
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-demo { background:#eff6ff; color:#1d4ed8; }
    .badge-review { background:#f5f3ff; color:#7c3aed; }
    .badge-viva { background:#ecfeff; color:#0891b2; }

    .meetings-page .btn-join {
        background: linear-gradient(135deg,#059669,#10b981);
        color:#fff;
        border-radius:10px;
        padding:8px 14px;
        font-size:13px;
        text-decoration:none;
    }

    .meetings-page .btn-open {
        background: linear-gradient(135deg,#1b00ff,#4338ca);
        color:#fff;
        border-radius:10px;
        padding:8px 14px;
        font-size:13px;
        text-decoration:none;
    }
</style>

<div class="pd-ltr-20 xs-pd-20-10 meetings-page">

    <!-- Header -->
    <div class="page-header-card mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3>{{ __('backend.supervisor_upcoming_meetings.heading') }}</h3>
                <p>{{ __('backend.supervisor_upcoming_meetings.subtitle') }}</p>
            </div>

            <div>
                <a href="{{ route('supervisor.meetings.create') }}" class="btn-outline-header">
                    {{ __('backend.supervisor_upcoming_meetings.create_meeting') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-number">{{ $totalMeetings }}</div>
                <small>{{ __('backend.supervisor_upcoming_meetings.total_upcoming') }}</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-number">{{ $todayMeetings }}</div>
                <small>{{ __('backend.supervisor_upcoming_meetings.today') }}</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-number">{{ $demoCount }}</div>
                <small>{{ __('backend.supervisor_upcoming_meetings.demo_meetings') }}</small>
            </div>
        </div>
    </div>

    <!-- Meetings -->
    <div class="row">
        @forelse($meetings as $meeting)
            @php
                $typeClass = match($meeting->meeting_type) {
                    'demo' => 'badge-demo',
                    'review' => 'badge-review',
                    'viva' => 'badge-viva',
                    default => ''
                };
            @endphp

            <div class="col-md-6 mb-4">
                <div class="meeting-card">

                    <!-- Title -->
                    <h5 class="mb-1">{{ $meeting->title }}</h5>

                    <!-- Project -->
                    <div class="text-muted mb-2">
                        {{ $meeting->project->name ?? __('backend.supervisor_upcoming_meetings.project') }}
                    </div>

                    <!-- Type -->
                    <span class="badge-soft {{ $typeClass }}">
                        {{ ucfirst($meeting->meeting_type) }}
                    </span>

                    <!-- Date -->
                    <div class="mt-3">
                        <strong>{{ $meeting->meeting_date }}</strong>  
                        <span class="text-muted">
                            {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 d-flex gap-2">

                        @if($meeting->meeting_link)
                            <a href="{{ $meeting->meeting_link }}" target="_blank" class="btn-join">
                                {{ __('backend.supervisor_upcoming_meetings.join_meeting') }}
                            </a>
                        @endif

                        <a href="{{ route('supervisor.projects.show', $meeting->project_id) }}" class="btn-open">
                            {{ __('backend.supervisor_upcoming_meetings.open_project') }}
                        </a>

                    </div>

                </div>
            </div>

        @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    {{ __('backend.supervisor_upcoming_meetings.no_upcoming_meetings') }}
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-3">
        {{ $meetings->links() }}
    </div>

</div>
@endsection