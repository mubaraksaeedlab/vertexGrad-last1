<div class="row g-3 stats-grid mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card stat-all">
            <div class="stat-top">
                <p class="stat-label">{{ __('backend.investors_index.total_investors') }}</p>
                <span class="stat-icon"><i class="bi bi-people-fill"></i></span>
            </div>
            <h3 class="stat-value">{{ $stats['total'] ?? 0 }}</h3>
            <div class="stat-note">{{ __('backend.investors_index.total_investors_note') }}</div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card stat-active">
            <div class="stat-top">
                <p class="stat-label">{{ __('backend.investors_index.active') }}</p>
                <span class="stat-icon"><i class="bi bi-check-circle-fill"></i></span>
            </div>
            <h3 class="stat-value">{{ $stats['active'] ?? 0 }}</h3>
            <div class="stat-note">{{ __('backend.investors_index.active_note') }}</div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card stat-disabled">
            <div class="stat-top">
                <p class="stat-label">{{ __('backend.investors_index.inactive') }}</p>
                <span class="stat-icon"><i class="bi bi-slash-circle-fill"></i></span>
            </div>
            <h3 class="stat-value">{{ $stats['inactive'] ?? 0 }}</h3>
            <div class="stat-note">{{ __('backend.investors_index.inactive_note') }}</div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card stat-pending">
            <div class="stat-top">
                <p class="stat-label">{{ __('backend.investors_index.archived') }}</p>
                <span class="stat-icon"><i class="bi bi-archive-fill"></i></span>
            </div>
            <h3 class="stat-value">{{ $stats['archived'] ?? 0 }}</h3>
            <div class="stat-note">{{ __('backend.investors_index.archived_note') }}</div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card stat-add">
            <div class="stat-top">
                <p class="stat-label">{{ __('backend.investors_index.total_budget') }}</p>
                <span class="stat-icon"><i class="bi bi-cash-stack"></i></span>
            </div>
            <h3 class="stat-value" style="font-size: 1.15rem;">${{ number_format($stats['budget'] ?? 0, 2) }}</h3>
            <div class="stat-note">{{ __('backend.investors_index.total_budget_note') }}</div>
        </div>
    </div>

    <div class="col-lg-2 col-md-4 col-sm-6">
        <div class="stat-card stat-inactive">
            <div class="stat-top">
                <p class="stat-label">{{ __('backend.investors_index.top_company') }}</p>
                <span class="stat-icon"><i class="bi bi-building-fill"></i></span>
            </div>
            <h3 class="stat-value" style="font-size: 1rem; line-height: 1.35;">
                {{ $stats['top_company']->company ?? __('backend.investors_index.not_available') }}
            </h3>
            <div class="stat-note">{{ __('backend.investors_index.top_company_note') }}</div>
        </div>
    </div>
</div>