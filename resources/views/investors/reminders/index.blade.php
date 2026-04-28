@extends('layouts.app')

@section('title', __('backend.investors_reminders.page_title'))

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="card-box p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4" style="gap:12px;">
            <div>
                <h4 class="mb-1">{{ $investor->user?->name ?? __('backend.investors_reminders.investor_reminders_fallback') }}</h4>
                <p class="text-muted mb-0">{{ __('backend.investors_reminders.subtitle') }}</p>
            </div>

            <div class="d-flex" style="gap:10px;">
                <a href="{{ route('admin.investors.show', $investor->user_id) }}" class="btn btn-light border">{{ __('backend.investors_reminders.back') }}</a>
                <a href="{{ route('admin.investors.reminders.create', $investor->id) }}" class="btn btn-primary">{{ __('backend.investors_reminders.new_reminder') }}</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('backend.investors_reminders.title') }}</th>
                        <th>{{ __('backend.investors_reminders.type') }}</th>
                        <th>{{ __('backend.investors_reminders.status') }}</th>
                        <th>{{ __('backend.investors_reminders.remind_at') }}</th>
                        <th>{{ __('backend.investors_reminders.delivery') }}</th>
                        <th>{{ __('backend.investors_reminders.created_by') }}</th>
                        <th>{{ __('backend.investors_reminders.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reminders as $reminder)
                        <tr>
                            <td>{{ $loop->iteration + ($reminders->currentPage() - 1) * $reminders->perPage() }}</td>
                            <td>{{ $reminder->title }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $reminder->type)) }}</td>
                            <td>{{ ucfirst($reminder->status) }}</td>
                            <td>{{ optional($reminder->remind_at)->format('Y-m-d h:i A') }}</td>
                            <td>
                                {{ __('backend.investors_reminders.in_app') }}: {{ $reminder->send_in_app ? __('backend.investors_reminders.yes') : __('backend.investors_reminders.no') }}<br>
                                {{ __('backend.investors_reminders.email') }}: {{ $reminder->send_email ? __('backend.investors_reminders.yes') : __('backend.investors_reminders.no') }}
                            </td>
                            <td>{{ optional($reminder->creator)->name ?? __('backend.investors_reminders.system') }}</td>
                            <td>
                                <div class="d-flex" style="gap:8px;">
                                    <a href="{{ route('admin.investors.reminders.edit', [$investor->id, $reminder->id]) }}" class="btn btn-sm btn-outline-primary">{{ __('backend.investors_reminders.edit') }}</a>

                                    <form action="{{ route('admin.investors.reminders.destroy', [$investor->id, $reminder->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('backend.investors_reminders.confirm_delete') }}')">{{ __('backend.investors_reminders.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">{{ __('backend.investors_reminders.no_reminders_found') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $reminders->links() }}
        </div>
    </div>
</div>
@endsection