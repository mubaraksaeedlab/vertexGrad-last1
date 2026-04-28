@extends('layouts.app')

@section('title', __('backend.investor_contracts.edit_page_title'))

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="card-box p-4">
        <h4 class="mb-4">
            {{ __('backend.investor_contracts.edit_heading', ['name' => $investor->user?->name]) }}
        </h4>

        <form action="{{ route('admin.investors.contracts.update', [$investor->id, $contract->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.title') }}</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $contract->title) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.type') }}</label>
                <input type="text" name="type" class="form-control" value="{{ old('type', $contract->type) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.status') }}</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status', $contract->status) === 'draft' ? 'selected' : '' }}>
                        {{ __('backend.investor_contracts.statuses.draft') }}
                    </option>
                    <option value="active" {{ old('status', $contract->status) === 'active' ? 'selected' : '' }}>
                        {{ __('backend.investor_contracts.statuses.active') }}
                    </option>
                    <option value="expired" {{ old('status', $contract->status) === 'expired' ? 'selected' : '' }}>
                        {{ __('backend.investor_contracts.statuses.expired') }}
                    </option>
                    <option value="cancelled" {{ old('status', $contract->status) === 'cancelled' ? 'selected' : '' }}>
                        {{ __('backend.investor_contracts.statuses.cancelled') }}
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.start_date') }}</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($contract->start_date)->format('Y-m-d')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.end_date') }}</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($contract->end_date)->format('Y-m-d')) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.replace_contract_file') }}</label>
                <input type="file" name="file" class="form-control">
            </div>

            <div class="mb-4">
                <label class="form-label">{{ __('backend.investor_contracts.notes') }}</label>
                <textarea name="notes" class="form-control" rows="5">{{ old('notes', $contract->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                {{ __('backend.investor_contracts.update_contract') }}
            </button>
        </form>
    </div>
</div>
@endsection