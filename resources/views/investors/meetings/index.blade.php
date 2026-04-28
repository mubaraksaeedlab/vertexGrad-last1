@extends('layouts.app')

@section('title', __('backend.investor_meetings_index.page_title'))

@section('content')
<style>
    .investor-meetings-page .page-header-card {
        background: linear-gradient(135deg, #0d1b4c 0%, #1b00ff 100%);
        border-radius: 22px;
        padding: 30px 32px;
        color: #fff;
        box-shadow: 0 14px 34px rgba(27, 0, 255, 0.18);
        margin-bottom: 24px;
    }

    .investor-meetings-page .section-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .investor-meetings-page .section-header {
        padding: 18px 22px;
        border-bottom: 1px solid #eef2f7;
        font-weight: 700;
        color: #0f172a;
    }

    .investor-meetings-page .modern-table {
        margin-bottom: 0;
        width: 100%;
    }

    .investor-meetings-page .modern-table thead th {
        background: #f8fafc;
        color: #334155;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 10px;
        font-size: 13px;
    }

    .investor-meetings-page .modern-table tbody td {
        padding: 12px 10px;
        border-color: #f1f5f9;
        font-size: 13px;
        vertical-align: middle;
    }

    .investor-meetings-page .btn-back,
    .investor-meetings-page .btn-add {
        border-radius: 12px;
        padding: 10px 16px;
        font-weight: 700;
        text-decoration: none;
    }

    .investor-meetings-page .btn-back {
        background: #fff;
        border: 1px solid #dbe4f0;
        color: #0f172a;
    }

    .investor-meetings-page .btn-add {
        background: linear-gradient(135deg, #1b00ff, #4f46e5);
        color: #fff;
    }

    .investor-meetings-page .badge-soft {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-scheduled { background: #eff6ff; color: #1d4ed8; }
    .badge-completed { background: #ecfdf5; color: #15803d; }
    .badge-cancelled { background: #fef2f2; color: #dc2626; }
</style>

<div class="pd-ltr-20 xs-pd-20-10 investor-meetings-page">
    <div class="min-height-200px">

        <div class="page-header-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap: 15px;">
                <div>
                    <h3 class="mb-1">{{ $investor->user?->name ?? __('backend.investor_meetings_index.page_title') }}</h3>
                    <p class="mb-0">{{ __('backend.investor_meetings_index.subtitle') }}</p>
                </div>

                <div class="d-flex flex-wrap" style="gap: 10px;">
                    <a href="{{ route('admin.investors.show', $investor->user_id) }}" class="btn-back">
                        <i class="fa fa-arrow-left mr-1"></i> {{ __('backend.investor_meetings_index.back') }}
                    </a>

                    <a href="{{ route('admin.investors.meetings.create', $investor->user_id) }}" class="btn-add">
                        <i class="fa fa-plus mr-1"></i> {{ __('backend.investor_meetings_index.new_meeting') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="section-card">
            <div class="section-header">{{ __('backend.investor_meetings_index.meetings_list') }}</div>

            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('backend.investor_meetings_index.title') }}</th>
                            <th>{{ __('backend.investor_meetings_index.type') }}</th>
                            <th>{{ __('backend.investor_meetings_index.status') }}</th>
                            <th>{{ __('backend.investor_meetings_index.date_time') }}</th>
                            <th>{{ __('backend.investor_meetings_index.location_link') }}</th>
                            <th>{{ __('backend.investor_meetings_index.created_by') }}</th>
                            <th>{{ __('backend.investor_meetings_index.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($meetings as $meeting)
                            @php
                                $statusClass = match($meeting->status) {
                                    'scheduled' => 'badge-scheduled',
                                    'completed' => 'badge-completed',
                                    'cancelled' => 'badge-cancelled',
                                    default => 'badge-scheduled',
                                };
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration + ($meetings->currentPage() - 1) * $meetings->perPage() }}</td>
                                <td>{{ $meeting->title }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $meeting->type)) }}</td>
                                <td><span class="badge-soft {{ $statusClass }}">{{ ucfirst($meeting->status) }}</span></td>
                                <td>{{ optional($meeting->meeting_at)->format('Y-m-d h:i A') }}</td>
                                <td>
                                    @if($meeting->meeting_link)
                                        <a href="{{ $meeting->meeting_link }}" target="_blank">{{ __('backend.investor_meetings_index.open_link') }}</a>
                                    @elseif($meeting->location)
                                        {{ $meeting->location }}
                                    @else
                                        {{ __('backend.investor_meetings_index.empty') }}
                                    @endif
                                </td>
                                <td>{{ optional($meeting->creator)->name ?? __('backend.investor_meetings_index.system') }}</td>
                                <td>
                                    <div class="d-flex" style="gap:8px;">
                                        <a href="{{ route('admin.investors.meetings.edit', [$investor->user_id, $meeting->id]) }}" class="btn btn-sm btn-outline-primary">{{ __('backend.investor_meetings_index.edit') }}</a>

                                        <form action="{{ route('admin.investors.meetings.destroy', [$investor->user_id, $meeting->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('backend.investor_meetings_index.confirm_delete') }}')">{{ __('backend.investor_meetings_index.delete') }}</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">{{ __('backend.investor_meetings_index.no_meetings_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $meetings->links() }}
            </div>
        </div>

    </div>
</div>
@endsection