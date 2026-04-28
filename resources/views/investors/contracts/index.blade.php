@extends('layouts.app')

@section('title', __('backend.investor_contracts.index_page_title'))

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="card-box p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4" style="gap:12px;">
            <div>
                <h4 class="mb-1">
                    {{ $investor->user?->name ?? __('backend.investor_contracts.index_heading_fallback') }}
                </h4>
                <p class="text-muted mb-0">{{ __('backend.investor_contracts.index_subtitle') }}</p>
            </div>

            <div class="d-flex" style="gap:10px;">
                <a href="{{ route('admin.investors.show', $investor->user_id) }}" class="btn btn-light border">
                    {{ __('backend.investor_contracts.back') }}
                </a>
                <a href="{{ route('admin.investors.contracts.create', $investor->user_id) }}" class="btn btn-primary">
                    {{ __('backend.investor_contracts.new_contract') }}
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('backend.investor_contracts.table_number') }}</th>
                        <th>{{ __('backend.investor_contracts.title') }}</th>
                        <th>{{ __('backend.investor_contracts.type') }}</th>
                        <th>{{ __('backend.investor_contracts.status') }}</th>
                        <th>{{ __('backend.investor_contracts.dates') }}</th>
                        <th>{{ __('backend.investor_contracts.file') }}</th>
                        <th>{{ __('backend.investor_contracts.created_by') }}</th>
                        <th>{{ __('backend.investor_contracts.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $contract)
                        <tr>
                            <td>{{ $loop->iteration + ($contracts->currentPage() - 1) * $contracts->perPage() }}</td>
                            <td>{{ $contract->title }}</td>
                            <td>{{ $contract->type ?? __('backend.investor_contracts.empty_value') }}</td>
                            <td>{{ ucfirst($contract->status) }}</td>
                            <td>
                                {{ optional($contract->start_date)->format('Y-m-d') ?? __('backend.investor_contracts.empty_value') }}
                                —
                                {{ optional($contract->end_date)->format('Y-m-d') ?? __('backend.investor_contracts.empty_value') }}
                            </td>
                            <td>
                                @if($contract->file_path)
                                    <a href="{{ asset('storage/' . $contract->file_path) }}" target="_blank">
                                        {{ $contract->file_name ?? __('backend.investor_contracts.open_file') }}
                                    </a>
                                @else
                                    {{ __('backend.investor_contracts.empty_value') }}
                                @endif
                            </td>
                            <td>{{ optional($contract->creator)->name ?? __('backend.investor_contracts.system') }}</td>
                            <td>
                                <div class="d-flex" style="gap:8px;">
                                    <a href="{{ route('admin.investors.contracts.edit', [$investor->user_id, $contract->id]) }}" class="btn btn-sm btn-outline-primary">
                                        {{ __('backend.investor_contracts.edit') }}
                                    </a>

                                    <form action="{{ route('admin.investors.contracts.destroy', [$investor->user_id, $contract->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('backend.investor_contracts.confirm_delete') }}')">
                                            {{ __('backend.investor_contracts.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                {{ __('backend.investor_contracts.no_contracts_found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $contracts->links() }}
        </div>
    </div>
</div>
@endsection