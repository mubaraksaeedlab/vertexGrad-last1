<div class="filter-panel">
    <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="panel-title mb-1">{{ __('backend.investors_index.filters') }}</h2>
            <div class="panel-subtitle">{{ __('backend.investors_index.filters_subtitle') }}</div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.investors.index', ['view' => 'active']) }}"
               class="reset-btn px-3 {{ $view === 'active' ? 'btn btn-primary text-white border-0' : '' }}">
                <i class="fa fa-users mr-1"></i> {{ __('backend.investors_index.active_investors') }}
            </a>

            <a href="{{ route('admin.investors.index', ['view' => 'archived']) }}"
               class="reset-btn px-3 {{ $view === 'archived' ? 'btn btn-primary text-white border-0' : '' }}">
                <i class="fa fa-archive mr-1"></i> {{ __('backend.investors_index.archived_investors') }}
            </a>

            <a href="{{ route('admin.investors.index', ['view' => 'all']) }}"
               class="reset-btn px-3 {{ $view === 'all' ? 'btn btn-primary text-white border-0' : '' }}">
                <i class="fa fa-list mr-1"></i> {{ __('backend.investors_index.all') }}
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.investors.index') }}" id="investorFilterForm">
        <input type="hidden" name="view" value="{{ request('view', 'active') }}">

        <div class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="filter-label">{{ __('backend.investors_index.search') }}</label>
                <input
                    type="text"
                    name="search"
                    class="form-control filter-input"
                    placeholder="{{ __('backend.investors_index.search_placeholder') }}"
                    value="{{ request('search') }}">
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="filter-label">{{ __('backend.investors_index.status') }}</label>
                <select name="status" class="form-select filter-select auto-submit-filter">
                    <option value="">{{ __('backend.investors_index.all_statuses') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('backend.investors_index.active') }}</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('backend.investors_index.inactive') }}</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="filter-label">{{ __('backend.investors_index.city') }}</label>
                <input
                    type="text"
                    name="city"
                    class="form-control filter-input"
                    placeholder="{{ __('backend.investors_index.city') }}"
                    value="{{ request('city') }}">
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="filter-label">{{ __('backend.investors_index.per_page') }}</label>
                <select name="per_page" class="form-select filter-select auto-submit-filter">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary search-btn w-100">
                        <i class="fa fa-search mr-1"></i> {{ __('backend.investors_index.filter') }}
                    </button>

                    <a href="{{ route('admin.investors.index', ['view' => request('view', 'active')]) }}"
                       class="reset-btn w-100">
                        {{ __('backend.investors_index.reset') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>