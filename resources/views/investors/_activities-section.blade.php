<div class="main-panel h-100">
    <div class="panel-head">
        <h2 class="panel-title">
            <i class="fa fa-history mr-2"></i>{{ __('backend.investors_show.activities') }}
        </h2>
        <div class="panel-subtitle">{{ __('backend.investors_show.activities_subtitle') }}</div>
    </div>

    <div class="table-wrap">
        @if($investor->activities->count() > 0)
            <div class="students-table-card">
                <div class="p-3 p-md-4">
                    <ul class="list-unstyled mb-0">
                        @foreach($investor->activities as $activity)
                            <li class="py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: #eef2f7 !important;">
                                <div class="d-flex flex-column gap-1">
                                    <div class="student-name-cell">
                                        {{ $activity->user?->name ?? __('backend.investors_show.system') }}
                                    </div>

                                    <div class="student-muted-cell">
                                        {{ $activity->created_at?->format('Y-m-d h:i A') }}
                                    </div>

                                    <div style="font-weight: 600; color: var(--text-main);">
                                        {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                    </div>

                                    @if(!empty($activity->meta))
                                        <div class="student-muted-cell mt-1">
                                            {{ is_array($activity->meta) ? json_encode($activity->meta) : $activity->meta }}
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @else
            <div class="students-table-card">
                <div class="empty-state">
                    <i class="fa fa-history mb-2"></i>
                    <div>{{ __('backend.investors_show.no_activities_recorded') }}</div>
                </div>
            </div>
        @endif
    </div>
</div>