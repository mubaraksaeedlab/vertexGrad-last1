@extends('layouts.app')

@section('title', __('backend.manager_dashboard.page_title'))

@section('content')
@php
    $chartDaily = $chartDaily ?? [
        'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        'submitted' => [4, 6, 5, 8, 7, 4, 6],
        'approved' => [2, 3, 4, 5, 5, 3, 4],
        'rejected' => [1, 1, 0, 2, 1, 1, 0],
    ];

    $chartWeekly = $chartWeekly ?? [
        'labels' => ['W1', 'W2', 'W3', 'W4'],
        'submitted' => [18, 24, 19, 27],
        'approved' => [10, 13, 12, 17],
        'rejected' => [4, 3, 5, 4],
    ];

    $chartMonthly = $chartMonthly ?? [
        'labels' => $months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        'submitted' => $submittedByMonth ?? [20, 24, 26, 22, 28, 31],
        'approved' => $activeByMonth ?? [10, 14, 15, 12, 18, 20],
        'rejected' => $rejectedByMonth ?? [4, 5, 3, 6, 4, 5],
    ];

    $reportsCount = $stats['reports'] ?? $reportsCount ?? 0;
@endphp

<div class="executive-dashboard-v3">

    <div class="platform-bar-v3">
        <div class="platform-bar-left">
            <div class="platform-bar-label">{{ __('backend.manager_dashboard.platform_overview') }}</div>
            <h1 class="platform-bar-title">{{ setting('platform_name', 'VertexGrad') }}</h1>
            <p class="platform-bar-text">
                {{ setting('platform_tagline', __('backend.manager_dashboard.default_platform_tagline')) }}
            </p>
        </div>

        <div class="platform-bar-right">
            <div class="platform-badge-v3">
                <i class="fa fa-chart-network"></i>
                <span>{{ __('backend.manager_dashboard.executive_dashboard') }}</span>
            </div>
        </div>
    </div>

    <div class="kpi-grid-v3">
        <div class="kpi-card-v3 kpi-card-primary">
            <div class="kpi-card-head-v3">
                <div>
                    <div class="kpi-label-v3">{{ __('backend.manager_dashboard.total_projects') }}</div>
                    <div class="kpi-value-v3">{{ number_format($stats['total_projects'] ?? 0) }}</div>
                    <div class="kpi-note-v3">{{ __('backend.manager_dashboard.total_projects_note') }}</div>
                </div>
                <div class="kpi-icon-v3"><i class="fa fa-briefcase"></i></div>
            </div>
        </div>

        <div class="kpi-card-v3 kpi-card-success">
            <div class="kpi-card-head-v3">
                <div>
                    <div class="kpi-label-v3">{{ __('backend.manager_dashboard.students') }}</div>
                    <div class="kpi-value-v3">{{ number_format($stats['students'] ?? 0) }}</div>
                    <div class="kpi-note-v3">{{ __('backend.manager_dashboard.students_note') }}</div>
                </div>
                <div class="kpi-icon-v3"><i class="fa fa-user-graduate"></i></div>
            </div>
        </div>

        <div class="kpi-card-v3 kpi-card-warning">
            <div class="kpi-card-head-v3">
                <div>
                    <div class="kpi-label-v3">{{ __('backend.manager_dashboard.investors') }}</div>
                    <div class="kpi-value-v3">{{ number_format($stats['investors'] ?? 0) }}</div>
                    <div class="kpi-note-v3">{{ __('backend.manager_dashboard.investors_note') }}</div>
                </div>
                <div class="kpi-icon-v3"><i class="fa fa-chart-line"></i></div>
            </div>
        </div>

        <div class="kpi-card-v3 kpi-card-violet">
            <div class="kpi-card-head-v3">
                <div>
                    <div class="kpi-label-v3">{{ __('backend.manager_dashboard.reports') }}</div>
                    <div class="kpi-value-v3">{{ number_format($reportsCount) }}</div>
                    <div class="kpi-note-v3">{{ __('backend.manager_dashboard.reports_note') }}</div>
                </div>
                <div class="kpi-icon-v3"><i class="fa fa-chart-pie"></i></div>
            </div>
        </div>
    </div>

    <div class="main-dashboard-grid-v3">
        <div class="main-left-v3">
            <div class="panel-card-v3 flow-panel-v3">
                <div class="panel-head-v3">
                    <div>
                        <div class="panel-title-v3">{{ __('backend.manager_dashboard.project_flow_intelligence') }}</div>
                        <div class="panel-subtitle-v3">
                            {{ __('backend.manager_dashboard.project_flow_intelligence_subtitle') }}
                        </div>
                    </div>

                    <div class="chart-range-switch-v3" id="chartRangeSwitch">
                        <button class="chart-range-btn-v3 active" data-range="daily">{{ __('backend.manager_dashboard.daily') }}</button>
                        <button class="chart-range-btn-v3" data-range="weekly">{{ __('backend.manager_dashboard.weekly') }}</button>
                        <button class="chart-range-btn-v3" data-range="monthly">{{ __('backend.manager_dashboard.monthly') }}</button>
                    </div>
                </div>

                <div id="flow-area-chart" class="flow-chart-box-v3"></div>

                <div class="mini-summary-row-v3">
                    <div class="mini-summary-card-v3 pending">
                        <span>{{ __('backend.manager_dashboard.pending_projects') }}</span>
                        <strong>{{ number_format($stats['pending_projects'] ?? 0) }}</strong>
                        <small>{{ __('backend.manager_dashboard.pending_projects_note') }}</small>
                    </div>

                    <div class="mini-summary-card-v3 active">
                        <span>{{ __('backend.manager_dashboard.active_projects') }}</span>
                        <strong>{{ number_format($stats['active_projects'] ?? 0) }}</strong>
                        <small>{{ __('backend.manager_dashboard.active_projects_note') }}</small>
                    </div>

                    <div class="mini-summary-card-v3 rejected">
                        <span>{{ __('backend.manager_dashboard.rejected_projects') }}</span>
                        <strong>{{ number_format($stats['rejected_projects'] ?? 0) }}</strong>
                        <small>{{ __('backend.manager_dashboard.rejected_projects_note') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-right-v3">
            <div class="panel-card-v3 status-panel-v3">
                <div class="panel-head-v3">
                    <div>
                        <div class="panel-title-v3">{{ __('backend.manager_dashboard.project_status_overview') }}</div>
                        <div class="panel-subtitle-v3">
                            {{ __('backend.manager_dashboard.project_status_overview_subtitle') }}
                        </div>
                    </div>
                </div>

                <div id="status-horizontal-chart" class="status-chart-box-v3"></div>
            </div>

            <div class="panel-card-v3 momentum-panel-v3">
                <div class="panel-head-v3">
                    <div>
                        <div class="panel-title-v3">{{ __('backend.manager_dashboard.approval_momentum') }}</div>
                        <div class="panel-subtitle-v3">
                            {{ __('backend.manager_dashboard.approval_momentum_subtitle') }}
                        </div>
                    </div>
                </div>

                <div id="approval-momentum-chart" class="approval-momentum-chart-v3"></div>

                <div class="approval-stats-v3">
                    <div class="approval-stat-card-v3 good">
                        <span>{{ __('backend.manager_dashboard.approved') }}</span>
                        <strong>{{ number_format($stats['active_projects'] ?? 0) }}</strong>
                    </div>

                    <div class="approval-stat-card-v3 bad">
                        <span>{{ __('backend.manager_dashboard.rejected') }}</span>
                        <strong>{{ number_format($stats['rejected_projects'] ?? 0) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lower-grid-v3">
        <div class="panel-card-v3">
            <div class="panel-head-v3">
                <div>
                    <div class="panel-title-v3 small">{{ __('backend.manager_dashboard.recent_students') }}</div>
                    <div class="panel-subtitle-v3">{{ __('backend.manager_dashboard.recent_students_subtitle') }}</div>
                </div>
            </div>

            <div class="clean-list-v3">
                @forelse($recentStudents as $student)
                    <div class="clean-row-v3">
                        <div class="clean-row-left-v3">
                            <img src="{{ asset('vendors/images/photo1.jpg') }}" class="avatar-v3" alt="">
                            <div>
                                <div class="row-title-v3">{{ $student->name }}</div>
                                <div class="row-meta-v3">{{ $student->email }}</div>
                            </div>
                        </div>

                        <span class="row-pill-v3">{{ $student->status ?? __('backend.manager_dashboard.active') }}</span>
                    </div>
                @empty
                    <div class="empty-box-v3">
                        <i class="fa fa-users mb-2"></i>
                        <span>{{ __('backend.manager_dashboard.no_recent_students_found') }}</span>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="panel-card-v3">
            <div class="panel-head-v3">
                <div>
                    <div class="panel-title-v3 small">{{ __('backend.manager_dashboard.recent_projects') }}</div>
                    <div class="panel-subtitle-v3">{{ __('backend.manager_dashboard.recent_projects_subtitle') }}</div>
                </div>
            </div>

            <div class="project-list-v3">
                @forelse($recentProjects->take(5) as $project)
                    <div class="project-card-v3">
                        <div class="project-card-top-v3">
                            <div class="project-name-v3">{{ $project->name }}</div>
                            <span class="project-status-v3 status-{{ strtolower($project->status) }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </div>

                        <div class="project-meta-v3">
                            <span>{{ $project->student->name ?? '—' }}</span>
                            <span>${{ number_format($project->budget ?? 0) }}</span>
                            <span>{{ $project->created_at?->format('d M Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-box-v3">
                        <i class="fa fa-folder-open mb-2"></i>
                        <span>{{ __('backend.manager_dashboard.no_recent_projects_found') }}</span>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="quick-wrap-v3">
        <div class="panel-head-v3 mb-0">
            <div>
                <div class="panel-title-v3">{{ __('backend.manager_dashboard.quick_access') }}</div>
                <div class="panel-subtitle-v3">{{ __('backend.manager_dashboard.quick_access_subtitle') }}</div>
            </div>
        </div>

        <div class="quick-grid-v3">
            <a href="{{ route('admin.projects.index') }}" class="quick-card-v3">
                <div class="quick-icon-v3 blue"><i class="fa fa-briefcase"></i></div>
                <div>
                    <strong>{{ __('backend.manager_dashboard.projects') }}</strong>
                    <span>{{ __('backend.manager_dashboard.projects_quick_note') }}</span>
                </div>
            </a>

            <a href="{{ route('admin.students.index') }}" class="quick-card-v3">
                <div class="quick-icon-v3 green"><i class="fa fa-user-graduate"></i></div>
                <div>
                    <strong>{{ __('backend.manager_dashboard.students') }}</strong>
                    <span>{{ __('backend.manager_dashboard.students_quick_note') }}</span>
                </div>
            </a>

            <a href="{{ route('admin.investors.index') }}" class="quick-card-v3">
                <div class="quick-icon-v3 amber"><i class="fa fa-chart-line"></i></div>
                <div>
                    <strong>{{ __('backend.manager_dashboard.investors') }}</strong>
                    <span>{{ __('backend.manager_dashboard.investors_quick_note') }}</span>
                </div>
            </a>

            <a href="{{ route('admin.reports.platform') }}" class="quick-card-v3">
                <div class="quick-icon-v3 violet"><i class="fa fa-chart-pie"></i></div>
                <div>
                    <strong>{{ __('backend.manager_dashboard.reports') }}</strong>
                    <span>{{ __('backend.manager_dashboard.reports_quick_note') }}</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.executive-dashboard-v3 {
    display: flex;
    flex-direction: column;
    gap: 22px;
    padding-bottom: 8px;
}

.platform-bar-v3 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
    background: linear-gradient(135deg, #ffffff 0%, #f8fbff 100%);
    border: 1px solid #e8eef7;
    border-radius: 30px;
    padding: 24px 26px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
}

.platform-bar-label {
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: 8px;
}

.platform-bar-title {
    color: #0f172a;
    font-size: 34px;
    font-weight: 900;
    line-height: 1.05;
    margin: 0 0 8px;
}

.platform-bar-text {
    color: #64748b;
    font-size: 14px;
    line-height: 1.8;
    margin: 0;
}

.platform-badge-v3 {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 16px;
    border-radius: 999px;
    background: linear-gradient(135deg, #eef4ff 0%, #f5f8ff 100%);
    color: #1d4ed8;
    font-size: 12px;
    font-weight: 800;
    white-space: nowrap;
}

.kpi-grid-v3 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.kpi-card-v3 {
    background: #fff;
    border: 1px solid #edf1f7;
    border-radius: 28px;
    padding: 20px;
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.05);
    transition: .22s ease;
}

.kpi-card-v3:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 38px rgba(15, 23, 42, 0.08);
}

.kpi-card-head-v3 {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: flex-start;
}

.kpi-label-v3 {
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 10px;
}

.kpi-value-v3 {
    color: #0f172a;
    font-size: 32px;
    line-height: 1.05;
    font-weight: 900;
    margin-bottom: 8px;
}

.kpi-note-v3 {
    color: #64748b;
    font-size: 12px;
    line-height: 1.6;
}

.kpi-icon-v3 {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.kpi-card-primary .kpi-icon-v3 { background: linear-gradient(135deg, rgba(0,184,217,.16), rgba(37,99,235,.12)); color: #0284c7; }
.kpi-card-success .kpi-icon-v3 { background: linear-gradient(135deg, rgba(34,197,94,.16), rgba(16,185,129,.12)); color: #15803d; }
.kpi-card-warning .kpi-icon-v3 { background: linear-gradient(135deg, rgba(245,158,11,.20), rgba(251,191,36,.10)); color: #b45309; }
.kpi-card-violet .kpi-icon-v3 { background: linear-gradient(135deg, rgba(139,92,246,.18), rgba(99,102,241,.12)); color: #6d28d9; }

.main-dashboard-grid-v3 {
    display: grid;
    grid-template-columns: 1.45fr .85fr;
    gap: 20px;
    align-items: stretch;
}

.main-left-v3,
.main-right-v3 {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.panel-card-v3 {
    background: #fff;
    border: 1px solid #edf1f7;
    border-radius: 30px;
    padding: 22px;
    box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
    height: 100%;
    overflow: hidden;
}

.flow-panel-v3 {
    min-height: auto;
}

.status-panel-v3 {
    min-height: 360px;
}

.momentum-panel-v3 {
    min-height: 440px;
    display: flex;
    flex-direction: column;
}

.panel-head-v3 {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 14px;
}

.panel-title-v3 {
    color: #0f172a;
    font-size: 22px;
    line-height: 1.1;
    font-weight: 900;
}

.panel-title-v3.small {
    font-size: 18px;
}

.panel-subtitle-v3 {
    color: #64748b;
    font-size: 13px;
    line-height: 1.75;
    margin-top: 6px;
}

.chart-range-switch-v3 {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 6px;
}

.chart-range-btn-v3 {
    border: 0;
    background: transparent;
    min-height: 36px;
    padding: 0 14px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 800;
    color: #64748b;
    transition: .2s ease;
}

.chart-range-btn-v3.active {
    background: linear-gradient(135deg, #0f4cff 0%, #2563eb 100%);
    color: #fff;
    box-shadow: 0 10px 18px rgba(37, 99, 235, 0.22);
}

.flow-chart-box-v3 {
    height: 250px;
    min-height: 250px;
    margin-bottom: 0;
}

.mini-summary-row-v3 {
    margin-top: 12px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.mini-summary-card-v3 {
    border-radius: 22px;
    padding: 16px;
    border: 1px solid #edf1f7;
}

.mini-summary-card-v3 span {
    display: block;
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 8px;
}

.mini-summary-card-v3 strong {
    display: block;
    color: #0f172a;
    font-size: 28px;
    line-height: 1.05;
    font-weight: 900;
    margin-bottom: 6px;
}

.mini-summary-card-v3 small {
    display: block;
    color: #64748b;
    font-size: 12px;
    line-height: 1.6;
}

.mini-summary-card-v3.pending {
    background: linear-gradient(180deg, #fff8ed 0%, #ffffff 100%);
    border-color: #fde7c3;
}

.mini-summary-card-v3.active {
    background: linear-gradient(180deg, #edfcf4 0%, #ffffff 100%);
    border-color: #d4f5e1;
}

.mini-summary-card-v3.rejected {
    background: linear-gradient(180deg, #fff1f2 0%, #ffffff 100%);
    border-color: #ffd8de;
}

.status-chart-box-v3 {
    height: 240px;
    min-height: 240px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.approval-momentum-chart-v3 {
    height: 170px;
    min-height: 170px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.approval-stats-v3 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: auto;
}

.approval-stat-card-v3 {
    border-radius: 20px;
    padding: 14px 16px;
    border: 1px solid #e8eef7;
    background: #fff;
}

.approval-stat-card-v3 span {
    display: block;
    font-size: 12px;
    font-weight: 800;
    margin-bottom: 8px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .05em;
}

.approval-stat-card-v3 strong {
    display: block;
    font-size: 28px;
    line-height: 1.05;
    font-weight: 900;
    color: #0f172a;
}

.approval-stat-card-v3.good {
    background: linear-gradient(180deg, #ecfdf3 0%, #ffffff 100%);
    border-color: #d7f5e3;
}

.approval-stat-card-v3.bad {
    background: linear-gradient(180deg, #fff1f2 0%, #ffffff 100%);
    border-color: #ffdadd;
}

.lower-grid-v3 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.clean-list-v3,
.project-list-v3 {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.clean-row-v3 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f4f8;
}

.clean-row-v3:last-child {
    border-bottom: 0;
}

.clean-row-left-v3 {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}

.avatar-v3 {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 3px solid #fff;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.10);
}

.row-title-v3 {
    color: #0f172a;
    font-size: 14px;
    font-weight: 800;
    line-height: 1.3;
}

.row-meta-v3 {
    color: #64748b;
    font-size: 12px;
    margin-top: 4px;
}

.row-pill-v3 {
    display: inline-flex;
    align-items: center;
    min-height: 30px;
    padding: 0 12px;
    border-radius: 999px;
    background: linear-gradient(135deg, #eef4ff 0%, #f5f8ff 100%);
    color: #1d4ed8;
    font-size: 11px;
    font-weight: 800;
    white-space: nowrap;
}

.project-card-v3 {
    border: 1px solid #eef2f7;
    background: #fbfdff;
    border-radius: 20px;
    padding: 14px;
}

.project-card-top-v3 {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    align-items: center;
    margin-bottom: 10px;
}

.project-name-v3 {
    color: #0f172a;
    font-size: 14px;
    font-weight: 800;
}

.project-meta-v3 {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    color: #64748b;
    font-size: 12px;
}

.project-status-v3 {
    display: inline-flex;
    align-items: center;
    min-height: 28px;
    padding: 0 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
}

.status-pending { background: #fff4db; color: #946200; }
.status-active { background: #e8f7ee; color: #1d7f49; }
.status-completed { background: #eaf2ff; color: #265ed7; }
.status-rejected { background: #fdecef; color: #b42318; }

.quick-wrap-v3 {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.quick-grid-v3 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

.quick-card-v3 {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    border-radius: 24px;
    border: 1px solid #edf1f7;
    background: #fff;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
    text-decoration: none;
    color: inherit;
    transition: .18s ease;
}

.quick-card-v3:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 28px rgba(15, 23, 42, 0.07);
    color: inherit;
}

.quick-icon-v3 {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.quick-icon-v3.blue { background: linear-gradient(135deg, rgba(37,99,235,.16), rgba(59,130,246,.10)); color: #1d4ed8; }
.quick-icon-v3.green { background: linear-gradient(135deg, rgba(34,197,94,.16), rgba(16,185,129,.10)); color: #15803d; }
.quick-icon-v3.amber { background: linear-gradient(135deg, rgba(245,158,11,.18), rgba(251,191,36,.10)); color: #b45309; }
.quick-icon-v3.violet { background: linear-gradient(135deg, rgba(139,92,246,.18), rgba(99,102,241,.10)); color: #7c3aed; }

.quick-card-v3 strong {
    display: block;
    color: #0f172a;
    font-size: 14px;
    font-weight: 800;
    margin-bottom: 4px;
}

.quick-card-v3 span {
    display: block;
    color: #64748b;
    font-size: 12px;
}

.empty-box-v3 {
    min-height: 220px;
    border: 1px dashed #dbe4ee;
    border-radius: 24px;
    background: #fafcff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    color: #94a3b8;
    font-weight: 700;
}

.empty-box-v3 i {
    font-size: 24px;
}

@media (max-width: 1399.98px) {
    .quick-grid-v3 {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 1199.98px) {
    .kpi-grid-v3,
    .main-dashboard-grid-v3,
    .lower-grid-v3 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 991.98px) {
    .mini-summary-row-v3,
    .quick-grid-v3 {
        grid-template-columns: 1fr 1fr;
    }

    .platform-bar-v3 {
        flex-direction: column;
        align-items: flex-start;
    }
}

@media (max-width: 767.98px) {
    .kpi-grid-v3,
    .mini-summary-row-v3,
    .lower-grid-v3,
    .quick-grid-v3,
    .approval-stats-v3 {
        grid-template-columns: 1fr;
    }

    .platform-bar-title {
        font-size: 28px;
    }

    .chart-range-switch-v3 {
        width: 100%;
        justify-content: space-between;
    }

    .chart-range-btn-v3 {
        flex: 1;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof ApexCharts === 'undefined') return;

    const chartSets = {
        daily: {
            labels: @json($chartDaily['labels']),
            submitted: @json($chartDaily['submitted']),
            approved: @json($chartDaily['approved']),
            rejected: @json($chartDaily['rejected'])
        },
        weekly: {
            labels: @json($chartWeekly['labels']),
            submitted: @json($chartWeekly['submitted']),
            approved: @json($chartWeekly['approved']),
            rejected: @json($chartWeekly['rejected'])
        },
        monthly: {
            labels: @json($chartMonthly['labels']),
            submitted: @json($chartMonthly['submitted']),
            approved: @json($chartMonthly['approved']),
            rejected: @json($chartMonthly['rejected'])
        }
    };

    const rangeButtons = document.querySelectorAll('.chart-range-btn-v3');
    const axisLabelColor = '#98a2b3';
    const gridColor = '#edf2f7';
    let currentRange = 'daily';

    const flowChart = new ApexCharts(document.querySelector("#flow-area-chart"), {
        series: [
            { name: @json(__('backend.manager_dashboard.submitted')), data: chartSets[currentRange].submitted },
            { name: @json(__('backend.manager_dashboard.approved')), data: chartSets[currentRange].approved },
            { name: @json(__('backend.manager_dashboard.rejected')), data: chartSets[currentRange].rejected }
        ],
        chart: {
            type: 'area',
            height: 250,
            toolbar: { show: false },
            foreColor: axisLabelColor
        },
        colors: ['#00b8d9', '#2563eb', '#ef4444'],
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth',
            width: 4
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.22,
                opacityTo: 0.03,
                stops: [0, 95, 100]
            }
        },
        markers: {
            size: 0,
            hover: { size: 6 }
        },
        grid: {
            borderColor: gridColor,
            strokeDashArray: 5,
            padding: { left: 8, right: 8, top: 0, bottom: -6 }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            fontWeight: 700
        },
        xaxis: {
            categories: chartSets[currentRange].labels,
            labels: {
                style: {
                    colors: Array(chartSets[currentRange].labels.length).fill(axisLabelColor)
                }
            }
        },
        yaxis: {
            labels: {
                style: { colors: [axisLabelColor] }
            }
        },
        tooltip: {
            theme: 'light',
            shared: true,
            intersect: false
        }
    });

    flowChart.render();

    rangeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            rangeButtons.forEach(x => x.classList.remove('active'));
            this.classList.add('active');
            currentRange = this.dataset.range;

            flowChart.updateOptions({
                xaxis: {
                    categories: chartSets[currentRange].labels,
                    labels: {
                        style: {
                            colors: Array(chartSets[currentRange].labels.length).fill(axisLabelColor)
                        }
                    }
                }
            });

            flowChart.updateSeries([
                { name: @json(__('backend.manager_dashboard.submitted')), data: chartSets[currentRange].submitted },
                { name: @json(__('backend.manager_dashboard.approved')), data: chartSets[currentRange].approved },
                { name: @json(__('backend.manager_dashboard.rejected')), data: chartSets[currentRange].rejected }
            ]);
        });
    });

    new ApexCharts(document.querySelector("#status-horizontal-chart"), {
        series: [
            {{ (int) ($stats['pending_projects'] ?? 0) }},
            {{ (int) ($stats['active_projects'] ?? 0) }},
            {{ (int) ($stats['completed_projects'] ?? 0) }},
            {{ (int) ($stats['rejected_projects'] ?? 0) }}
        ],
        chart: {
            type: 'donut',
            height: 240
        },
        labels: [
            @json(__('backend.manager_dashboard.pending')),
            @json(__('backend.manager_dashboard.active')),
            @json(__('backend.manager_dashboard.completed')),
            @json(__('backend.manager_dashboard.rejected'))
        ],
        colors: ['#f59e0b', '#16a34a', '#2563eb', '#dc2626'],
        legend: {
            position: 'bottom',
            fontWeight: 700,
            fontSize: '13px'
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 0
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '14px',
                            color: '#64748b'
                        },
                        value: {
                            show: true,
                            fontSize: '30px',
                            fontWeight: 900,
                            color: '#0f172a'
                        },
                        total: {
                            show: true,
                            label: @json(__('backend.manager_dashboard.projects')),
                            fontSize: '13px',
                            fontWeight: 700,
                            color: '#64748b',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        },
        tooltip: {
            theme: 'light'
        }
    }).render();

    new ApexCharts(document.querySelector("#approval-momentum-chart"), {
        series: [
            {
                name: @json(__('backend.manager_dashboard.projects')),
                data: [
                    {{ (int) ($stats['active_projects'] ?? 0) }},
                    {{ (int) ($stats['rejected_projects'] ?? 0) }}
                ]
            }
        ],
        chart: {
            type: 'bar',
            height: 170,
            toolbar: { show: false }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 12,
                columnWidth: '46%',
                distributed: true
            }
        },
        colors: ['#16a34a', '#dc2626'],
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '13px',
                fontWeight: 800,
                colors: ['#ffffff']
            }
        },
        xaxis: {
            categories: [
                @json(__('backend.manager_dashboard.approved')),
                @json(__('backend.manager_dashboard.rejected'))
            ],
            labels: {
                style: {
                    colors: ['#475467', '#475467'],
                    fontSize: '12px',
                    fontWeight: 700
                }
            }
        },
        yaxis: {
            labels: {
                style: { colors: ['#98a2b3'] }
            }
        },
        grid: {
            borderColor: '#edf2f7',
            strokeDashArray: 4,
            padding: {
                bottom: 0
            }
        },
        tooltip: {
            theme: 'light'
        }
    }).render();
});
</script>
@endpush