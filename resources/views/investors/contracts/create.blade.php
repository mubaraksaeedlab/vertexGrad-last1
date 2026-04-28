@extends('layouts.app')

@section('title', __('backend.investor_contracts.create_page_title'))

@section('content')
<div class="pd-ltr-20 xs-pd-20-10">
    <div class="card-box p-4">
        <h4 class="mb-4">
            {{ __('backend.investor_contracts.create_heading', ['name' => $investor->user?->name]) }}
        </h4>

        <form action="{{ route('admin.investors.contracts.store', $investor->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.title') }}</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.type') }}</label>
                <input type="text" name="type" class="form-control" value="{{ old('type') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.status') }}</label>
                <select name="status" class="form-select">
                    <option value="draft">{{ __('backend.investor_contracts.statuses.draft') }}</option>
                    <option value="active">{{ __('backend.investor_contracts.statuses.active') }}</option>
                    <option value="expired">{{ __('backend.investor_contracts.statuses.expired') }}</option>
                    <option value="cancelled">{{ __('backend.investor_contracts.statuses.cancelled') }}</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.start_date') }}</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.end_date') }}</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('backend.investor_contracts.contract_file') }}</label>
                <input type="file" name="file" class="form-control">
            </div>

            <div class="mb-4">
                <label class="form-label">{{ __('backend.investor_contracts.notes') }}</label>
                <textarea name="notes" class="form-control" rows="5">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                {{ __('backend.investor_contracts.save_contract') }}
            </button>
        </form>
    </div>
</div>
@endsection