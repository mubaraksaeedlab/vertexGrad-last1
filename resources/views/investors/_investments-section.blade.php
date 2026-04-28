<div class="main-panel mb-4">
    <div class="panel-head">
        <h2 class="panel-title">
            <i class="fa fa-hand-holding-usd mr-2"></i> {{ __('backend.investors_show.funding_project_investments') }}
        </h2>
        <div class="panel-subtitle">{{ __('backend.investors_show.funding_project_investments_subtitle') }}</div>
    </div>

    <div class="table-wrap">
        @if($projectInvestments->count() > 0)
            <div class="table-responsive students-table-card">
                <table class="table students-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{ __('backend.investors_show.project') }}</th>
                            <th>{{ __('backend.investors_show.student') }}</th>
                            <th class="text-center">{{ __('backend.investors_show.status') }}</th>
                            <th class="text-center">{{ __('backend.investors_show.amount') }}</th>
                            <th>{{ __('backend.investors_show.message') }}</th>
                            <th class="text-center">{{ __('backend.investors_show.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projectInvestments as $project)
                            @php
                                $fundingClass = match($project->pivot->status) {
                                    'approved' => 'badge-funding-approved',
                                    'rejected' => 'badge-funding-rejected',
                                    'requested' => 'badge-funding-requested',
                                    'interested' => 'badge-funding-interested',
                                    default => 'badge-default',
                                };
                            @endphp

                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>

                                <td>
                                    <div class="student-name-cell">{{ $project->name ?? __('backend.investors_show.untitled_project') }}</div>
                                    <div class="student-muted-cell">{{ __('backend.investors_show.project_id') }} {{ $project->project_id ?? __('backend.investors_show.empty') }}</div>
                                </td>

                                <td>
                                    <div class="student-muted-cell">{{ $project->student->name ?? __('backend.investors_show.empty') }}</div>
                                </td>

                                <td class="text-center">
                                    <span class="badge-soft {{ $fundingClass }}">
                                        {{ ucfirst($project->pivot->status ?? __('backend.investors_show.empty')) }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <div class="student-muted-cell">
                                        {{ $project->pivot->amount !== null ? '$' . number_format($project->pivot->amount, 2) : __('backend.investors_show.empty') }}
                                    </div>
                                </td>

                                <td style="max-width: 260px;">
                                    <div class="student-muted-cell" style="white-space: normal; line-height: 1.6;">
                                        {{ $project->pivot->message ?? __('backend.investors_show.empty') }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    <div class="student-muted-cell">
                                        {{ optional($project->pivot->created_at)->format('Y-m-d h:i A') ?? __('backend.investors_show.empty') }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="students-table-card">
                <div class="empty-state">
                    <i class="fa fa-hand-holding-usd mb-2"></i>
                    <div>{{ __('backend.investors_show.no_funding_activity_found') }}</div>
                </div>
            </div>
        @endif
    </div>
</div>