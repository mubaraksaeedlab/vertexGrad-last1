<div class="card border-0 shadow-sm">
    <div class="card-body">
        @if($projects->count())
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('backend.supervisor_projects_table.project') }}</th>
                            <th>{{ __('backend.supervisor_projects_table.student') }}</th>
                            <th>{{ __('backend.supervisor_projects_table.status') }}</th>
                            <th>{{ __('backend.supervisor_projects_table.scanner') }}</th>
                            <th>{{ __('backend.supervisor_projects_table.score') }}</th>
                            <th>{{ __('backend.supervisor_projects_table.supervisor_review') }}</th>
                            <th>{{ __('backend.supervisor_projects_table.updated') }}</th>
                            <th class="text-end">{{ __('backend.supervisor_projects_table.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->project_id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $project->name }}</div>
                                    <small class="text-muted">{{ $project->category ?? '—' }}</small>
                                </td>
                                <td>{{ $project->student->name ?? __('backend.supervisor_projects_table.not_available') }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $project->status ?? __('backend.supervisor_projects_table.not_available') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $project->scanner_status ?? __('backend.supervisor_projects_table.not_available') }}
                                    </span>
                                </td>
                                <td>{{ $project->scan_score ?? '—' }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $project->supervisor_status ?? __('backend.supervisor_projects_table.not_reviewed') }}
                                    </span>
                                </td>
                                <td>{{ optional($project->updated_at)->format('Y-m-d h:i A') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('supervisor.projects.show', $project->project_id) }}" class="btn btn-sm btn-primary">
                                        {{ __('backend.supervisor_projects_table.open') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $projects->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fa fa-folder-open-o fa-3x text-muted mb-3"></i>
                <h6 class="mb-1">{{ __('backend.supervisor_projects_table.no_projects_found') }}</h6>
                <p class="text-muted mb-0">{{ __('backend.supervisor_projects_table.no_projects_found_text') }}</p>
            </div>
        @endif
    </div>
</div>