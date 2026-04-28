@extends('supervisor.layout.app_super')

@section('title', __('backend.supervisor_dashboard.page_title'))

@section('content')
@php
    $totalProjects    = $stats['total_projects'] ?? 0;
    $pendingReviews   = $stats['pending_reviews'] ?? 0;
    $approvedProjects = $stats['approved_projects'] ?? 0;
    $revisionRequests = $stats['revision_requested'] ?? 0;

    $pendingPercent   = $totalProjects > 0 ? round(($pendingReviews / $totalProjects) * 100) : 0;
    $approvedPercent  = $totalProjects > 0 ? round(($approvedProjects / $totalProjects) * 100) : 0;
    $revisionPercent  = $totalProjects > 0 ? round(($revisionRequests / $totalProjects) * 100) : 0;

    $avgScanScore = $latestProjects->whereNotNull('scan_score')->avg('scan_score');
    $avgScanScore = $avgScanScore ? round($avgScanScore, 1) : 0;

    $completedScan = $latestProjects->where('scanner_status', 'completed')->count();
    $pendingScan   = $latestProjects->where('scanner_status', 'pending')->count();
    $failedScan    = $latestProjects->where('scanner_status', 'failed')->count();

    $chartPending  = max($pendingReviews, 0);
    $chartApproved = max($approvedProjects, 0);
    $chartRevision = max($revisionRequests, 0);

    $barMax = max($chartPending, $chartApproved, $chartRevision, 1);

    $featuredAnnouncement = isset($announcements) && $announcements->count() ? $announcements->first() : null;
    $otherAnnouncements = isset($announcements) && $announcements->count() > 1 ? $announcements->slice(1, 4) : collect();
@endphp

<style>
.supervisor-dashboard-page {
    color: var(--text, #0f172a);
}

.supervisor-dashboard-page .super-hero-card {
    background: linear-gradient(135deg, #081f5c 0%, #1b00ff 55%, #4338ca 100%);
    border-radius: 26px;
    padding: 34px 32px;
    color: #fff;
    box-shadow: 0 18px 45px rgba(27, 0, 255, 0.20);
    position: relative;
    overflow: hidden;
}

.supervisor-dashboard-page .super-hero-card::before {
    content: "";
    position: absolute;
    width: 280px;
    height: 280px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    top: -80px;
    right: -60px;
}

.supervisor-dashboard-page .super-hero-card::after {
    content: "";
    position: absolute;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    bottom: -60px;
    left: -40px;
}

.supervisor-dashboard-page .hero-content,
.supervisor-dashboard-page .hero-summary-card {
    position: relative;
    z-index: 2;
}

.supervisor-dashboard-page .hero-eyebrow {
    display: inline-block;
    padding: 7px 12px;
    border-radius: 999px;
    background: rgba(255,255,255,0.14);
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 14px;
    letter-spacing: .4px;
}

.supervisor-dashboard-page .hero-title {
    font-size: 34px;
    font-weight: 800;
    margin-bottom: 12px;
    color: #fff;
}

.supervisor-dashboard-page .hero-text {
    color: rgba(255,255,255,.88);
    max-width: 760px;
    line-height: 1.8;
    margin-bottom: 22px;
}

.supervisor-dashboard-page .hero-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.supervisor-dashboard-page .hero-btn {
    display: inline-flex;
    align-items: center;
    border-radius: 14px;
    padding: 11px 18px;
    font-weight: 700;
    text-decoration: none;
    transition: .25s ease;
}

.supervisor-dashboard-page .hero-btn:hover {
    text-decoration: none;
    transform: translateY(-2px);
}

.supervisor-dashboard-page .hero-btn-primary {
    background: #fff;
    color: #1b00ff;
    box-shadow: 0 10px 20px rgba(0,0,0,0.10);
}

.supervisor-dashboard-page .hero-btn-primary:hover {
    color: #1b00ff;
}

.supervisor-dashboard-page .hero-btn-outline {
    border: 1px solid rgba(255,255,255,0.30);
    color: #fff;
    background: rgba(255,255,255,0.08);
}

.supervisor-dashboard-page .hero-btn-outline:hover {
    color: #fff;
    background: rgba(255,255,255,0.14);
}

.supervisor-dashboard-page .hero-summary-card {
    background: rgba(255,255,255,0.10);
    border: 1px solid rgba(255,255,255,0.14);
    border-radius: 22px;
    padding: 22px;
    backdrop-filter: blur(8px);
}

.supervisor-dashboard-page .hero-summary-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    color: rgba(255,255,255,.92);
    font-weight: 700;
}

.supervisor-dashboard-page .hero-summary-badge {
    background: rgba(255,255,255,0.12);
    padding: 6px 10px;
    border-radius: 999px;
    font-size: 12px;
}

.supervisor-dashboard-page .hero-summary-score {
    text-align: center;
    margin-bottom: 18px;
}

.supervisor-dashboard-page .score-number {
    font-size: 42px;
    font-weight: 800;
    line-height: 1;
    color: #fff;
}

.supervisor-dashboard-page .score-label {
    margin-top: 6px;
    color: rgba(255,255,255,.78);
    font-size: 13px;
    font-weight: 600;
}

.supervisor-dashboard-page .hero-summary-metrics {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
}

.supervisor-dashboard-page .metric-item {
    background: rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 12px 10px;
    text-align: center;
}

.supervisor-dashboard-page .metric-item span {
    display: block;
    font-size: 12px;
    color: rgba(255,255,255,.74);
    margin-bottom: 4px;
}

.supervisor-dashboard-page .metric-item strong {
    font-size: 20px;
    color: #fff;
}

/* =========================
   Announcements
========================= */
.supervisor-dashboard-page .announcement-main-card {
    position: relative;
    overflow: hidden;
    border-radius: 26px;
    padding: 28px 28px 24px;
    background: var(--announcement-bg, linear-gradient(135deg, rgba(27, 0, 255, 0.08), rgba(6, 182, 212, 0.06)));
    border: 1px solid var(--announcement-border, rgba(27, 0, 255, 0.14));
    box-shadow: var(--card-shadow, 0 18px 45px rgba(15, 23, 42, 0.06));
    color: var(--announcement-text, var(--text, #0f172a));
}

.supervisor-dashboard-page .announcement-main-card::before {
    content: "";
    position: absolute;
    top: -55px;
    right: -45px;
    width: 180px;
    height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.10);
    pointer-events: none;
}

.supervisor-dashboard-page .announcement-main-card::after {
    content: "";
    position: absolute;
    bottom: -60px;
    left: -40px;
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    pointer-events: none;
}

.supervisor-dashboard-page .announcement-main-content {
    position: relative;
    z-index: 2;
}

.supervisor-dashboard-page .announcement-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 14px;
}

.supervisor-dashboard-page .announcement-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    border: 1px solid var(--border, #dbe3ef);
    background: rgba(255,255,255,0.55);
    color: var(--text, #0f172a);
    backdrop-filter: blur(8px);
}

html[data-theme="dark"] .supervisor-dashboard-page .announcement-badge {
    background: rgba(255,255,255,0.08);
    color: var(--text, #e5eefc);
}

.supervisor-dashboard-page .announcement-badge.pinned {
    background: rgba(245, 158, 11, 0.12);
    border-color: rgba(245, 158, 11, 0.24);
    color: #d97706;
}

html[data-theme="dark"] .supervisor-dashboard-page .announcement-badge.pinned {
    color: #fbbf24;
}

.supervisor-dashboard-page .announcement-badge.expire {
    background: rgba(239, 68, 68, 0.10);
    border-color: rgba(239, 68, 68, 0.18);
    color: #dc2626;
}

html[data-theme="dark"] .supervisor-dashboard-page .announcement-badge.expire {
    color: #fca5a5;
}

.supervisor-dashboard-page .announcement-title {
    font-size: 28px;
    font-weight: 800;
    line-height: 1.25;
    margin-bottom: 10px;
    color: var(--text, #0f172a);
}

html[data-theme="dark"] .supervisor-dashboard-page .announcement-title {
    color: var(--text, #e5eefc);
}

.supervisor-dashboard-page .announcement-text {
    font-size: 14px;
    line-height: 1.95;
    color: var(--text-soft, #64748b);
    max-width: 980px;
    margin-bottom: 0;
}

.supervisor-dashboard-page .announcement-grid {
    margin-top: 16px;
}

.supervisor-dashboard-page .announcement-mini-card {
    height: 100%;
    border-radius: 20px;
    border: 1px solid var(--border, #edf2f7);
    background: var(--surface, #fff);
    box-shadow: var(--soft-shadow, 0 10px 24px rgba(15, 23, 42, 0.05));
    padding: 18px;
}

.supervisor-dashboard-page .announcement-mini-title {
    font-size: 16px;
    font-weight: 800;
    color: var(--text, #0f172a);
    margin-bottom: 8px;
    line-height: 1.5;
}

.supervisor-dashboard-page .announcement-mini-text {
    font-size: 13px;
    line-height: 1.8;
    color: var(--text-soft, #64748b);
    margin-bottom: 12px;
}

.supervisor-dashboard-page .announcement-mini-meta {
    font-size: 11px;
    font-weight: 700;
    color: var(--text-soft, #64748b);
}

/* =========================
   KPI
========================= */
.supervisor-dashboard-page .kpi-card {
    border-radius: 22px;
    padding: 22px;
    display: flex;
    align-items: center;
    gap: 18px;
    background: var(--surface, #fff);
    border: 1px solid var(--border, #edf2f7);
    box-shadow: var(--card-shadow, 0 12px 28px rgba(15, 23, 42, 0.06));
    height: 100%;
    transition: .25s ease;
}

.supervisor-dashboard-page .kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 34px rgba(15, 23, 42, 0.10);
}

.supervisor-dashboard-page .kpi-icon {
    width: 62px;
    height: 62px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: #fff;
    flex-shrink: 0;
}

.supervisor-dashboard-page .kpi-primary .kpi-icon { background: linear-gradient(135deg, #1b00ff, #4f46e5); }
.supervisor-dashboard-page .kpi-warning .kpi-icon { background: linear-gradient(135deg, #d97706, #f59e0b); }
.supervisor-dashboard-page .kpi-success .kpi-icon { background: linear-gradient(135deg, #16a34a, #22c55e); }
.supervisor-dashboard-page .kpi-danger .kpi-icon { background: linear-gradient(135deg, #dc2626, #ef4444); }

.supervisor-dashboard-page .kpi-label {
    color: var(--text-soft, #64748b);
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 6px;
}

.supervisor-dashboard-page .kpi-value {
    font-size: 30px;
    font-weight: 800;
    color: var(--text, #0f172a);
    line-height: 1;
    margin-bottom: 7px;
}

.supervisor-dashboard-page .kpi-sub {
    color: var(--text-soft, #94a3b8);
    font-size: 12px;
    font-weight: 600;
}

/* =========================
   Panels
========================= */
.supervisor-dashboard-page .dashboard-panel {
    background: var(--surface, #fff);
    border-radius: 24px;
    border: 1px solid var(--border, #edf2f7);
    box-shadow: var(--card-shadow, 0 12px 28px rgba(15, 23, 42, 0.06));
    overflow: hidden;
}

.supervisor-dashboard-page .panel-header {
    padding: 22px 24px 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}

.supervisor-dashboard-page .panel-header h5 {
    margin: 0;
    font-weight: 800;
    color: var(--text, #0f172a);
}

.supervisor-dashboard-page .panel-header p {
    color: var(--text-soft, #64748b);
    font-size: 13px;
}

.supervisor-dashboard-page .panel-action-btn {
    border-radius: 12px;
    padding: 9px 14px;
    background: color-mix(in srgb, var(--primary, #1b00ff) 10%, transparent);
    color: var(--primary, #1b00ff);
    text-decoration: none;
    font-weight: 700;
    font-size: 13px;
}

.supervisor-dashboard-page .panel-action-btn:hover {
    color: var(--primary, #1b00ff);
    text-decoration: none;
    background: color-mix(in srgb, var(--primary, #1b00ff) 16%, transparent);
}

.supervisor-dashboard-page .donut-wrap {
    display: flex;
    justify-content: center;
    padding: 10px 0 20px;
}

.supervisor-dashboard-page .donut-chart {
    width: 230px;
    height: 230px;
    border-radius: 50%;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.supervisor-dashboard-page .donut-inner {
    width: 138px;
    height: 138px;
    border-radius: 50%;
    background: var(--surface, #fff);
    box-shadow: inset 0 0 0 1px var(--border, #e2e8f0);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.supervisor-dashboard-page .donut-total {
    font-size: 34px;
    font-weight: 800;
    color: var(--text, #0f172a);
    line-height: 1;
}

.supervisor-dashboard-page .donut-label {
    margin-top: 6px;
    font-size: 13px;
    color: var(--text-soft, #64748b);
    font-weight: 700;
}

.supervisor-dashboard-page .legend-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    padding: 0 24px 24px;
}

.supervisor-dashboard-page .legend-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--surface-2, #f8fafc);
    border-radius: 16px;
    padding: 12px;
}

.supervisor-dashboard-page .legend-item strong {
    display: block;
    color: var(--text, #0f172a);
    font-size: 17px;
}

.supervisor-dashboard-page .legend-item small {
    color: var(--text-soft, #64748b);
    font-weight: 600;
}

.supervisor-dashboard-page .legend-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    flex-shrink: 0;
}

.supervisor-dashboard-page .legend-warning { background: #f59e0b; }
.supervisor-dashboard-page .legend-success { background: #22c55e; }
.supervisor-dashboard-page .legend-danger { background: #ef4444; }

.supervisor-dashboard-page .bar-chart-wrap {
    height: 290px;
    display: flex;
    align-items: end;
    justify-content: space-around;
    gap: 20px;
    padding: 20px 24px 26px;
}

.supervisor-dashboard-page .bar-group {
    width: 100%;
    max-width: 110px;
    text-align: center;
}

.supervisor-dashboard-page .bar-value {
    font-size: 18px;
    font-weight: 800;
    color: var(--text, #0f172a);
    margin-bottom: 10px;
}

.supervisor-dashboard-page .bar-track {
    height: 200px;
    border-radius: 18px;
    background: var(--surface-2, #f1f5f9);
    position: relative;
    display: flex;
    align-items: end;
    overflow: hidden;
}

.supervisor-dashboard-page .bar-fill {
    width: 100%;
    border-radius: 18px 18px 0 0;
    transition: .3s ease;
}

.supervisor-dashboard-page .bar-fill-warning { background: linear-gradient(180deg, #fbbf24, #f59e0b); }
.supervisor-dashboard-page .bar-fill-success { background: linear-gradient(180deg, #4ade80, #22c55e); }
.supervisor-dashboard-page .bar-fill-danger { background: linear-gradient(180deg, #f87171, #ef4444); }

.supervisor-dashboard-page .bar-label {
    margin-top: 12px;
    font-size: 13px;
    font-weight: 700;
    color: var(--text-soft, #475569);
}

.supervisor-dashboard-page .progress-block {
    padding: 0 24px 24px;
}

.supervisor-dashboard-page .progress-row {
    margin-bottom: 22px;
}

.supervisor-dashboard-page .progress-row:last-child {
    margin-bottom: 0;
}

.supervisor-dashboard-page .progress-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    color: var(--text, #0f172a);
    font-weight: 700;
}

.supervisor-dashboard-page .custom-progress {
    height: 14px;
    background: var(--surface-2, #edf2f7);
    border-radius: 999px;
    overflow: hidden;
}

.supervisor-dashboard-page .custom-progress-bar {
    height: 100%;
    border-radius: 999px;
}

.supervisor-dashboard-page .custom-progress-bar.warning { background: linear-gradient(90deg, #fbbf24, #f59e0b); }
.supervisor-dashboard-page .custom-progress-bar.success { background: linear-gradient(90deg, #4ade80, #22c55e); }
.supervisor-dashboard-page .custom-progress-bar.danger { background: linear-gradient(90deg, #f87171, #ef4444); }

.supervisor-dashboard-page .scan-metric-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    padding: 0 24px 24px;
}

.supervisor-dashboard-page .scan-metric-card {
    border: 1px solid var(--border, #edf2f7);
    border-radius: 18px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    background: color-mix(in srgb, var(--surface, #fff) 92%, var(--primary, #1b00ff) 8%);
}

.supervisor-dashboard-page .scan-metric-icon {
    width: 50px;
    height: 50px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 18px;
    flex-shrink: 0;
}

.supervisor-dashboard-page .scan-blue { background: linear-gradient(135deg, #2563eb, #3b82f6); }
.supervisor-dashboard-page .scan-green { background: linear-gradient(135deg, #16a34a, #22c55e); }
.supervisor-dashboard-page .scan-orange { background: linear-gradient(135deg, #d97706, #f59e0b); }
.supervisor-dashboard-page .scan-red { background: linear-gradient(135deg, #dc2626, #ef4444); }

.supervisor-dashboard-page .scan-metric-text span {
    display: block;
    font-size: 12px;
    color: var(--text-soft, #64748b);
    font-weight: 700;
    margin-bottom: 3px;
}

.supervisor-dashboard-page .scan-metric-text strong {
    font-size: 24px;
    color: var(--text, #0f172a);
    font-weight: 800;
}

/* =========================
   Table
========================= */
.supervisor-dashboard-page .dashboard-table {
    width: 100%;
    table-layout: fixed;
    background: transparent;
}

.supervisor-dashboard-page .dashboard-table thead th {
    background: var(--surface-2, #f8fafc);
    border-bottom: 1px solid var(--border, #e2e8f0);
    color: var(--text-soft, #334155);
    font-size: 13px;
    font-weight: 800;
    padding: 14px 16px;
    white-space: nowrap;
}

.supervisor-dashboard-page .dashboard-table tbody td {
    padding: 16px;
    vertical-align: middle;
    border-color: color-mix(in srgb, var(--border, #f1f5f9) 72%, transparent);
    overflow: hidden;
    background: transparent;
}

.supervisor-dashboard-page .dashboard-table tbody tr:hover {
    background: color-mix(in srgb, var(--primary, #1b00ff) 3%, transparent);
}

.supervisor-dashboard-page .project-cell-title {
    font-weight: 700;
    color: var(--text, #0f172a);
    line-height: 1.4;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.supervisor-dashboard-page .project-cell-sub {
    font-size: 12px;
    color: var(--text-soft, #64748b);
    margin-top: 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.supervisor-dashboard-page .badge-soft {
    display: inline-block;
    padding: 7px 11px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .2px;
}

.supervisor-dashboard-page .badge-soft-primary { background: #eff6ff; color: #1d4ed8; }
.supervisor-dashboard-page .badge-soft-warning { background: #fff7ed; color: #c2410c; }
.supervisor-dashboard-page .badge-soft-success { background: #ecfdf5; color: #15803d; }
.supervisor-dashboard-page .badge-soft-danger { background: #fef2f2; color: #dc2626; }
.supervisor-dashboard-page .badge-soft-secondary { background: #f1f5f9; color: #475569; }

html[data-theme="dark"] .supervisor-dashboard-page .badge-soft-primary { background: rgba(59,130,246,.12); color: #93c5fd; }
html[data-theme="dark"] .supervisor-dashboard-page .badge-soft-warning { background: rgba(245,158,11,.12); color: #fcd34d; }
html[data-theme="dark"] .supervisor-dashboard-page .badge-soft-success { background: rgba(34,197,94,.12); color: #86efac; }
html[data-theme="dark"] .supervisor-dashboard-page .badge-soft-danger { background: rgba(239,68,68,.12); color: #fca5a5; }
html[data-theme="dark"] .supervisor-dashboard-page .badge-soft-secondary { background: rgba(148,163,184,.12); color: #cbd5e1; }

.supervisor-dashboard-page .score-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 62px;
    height: 34px;
    border-radius: 12px;
    background: linear-gradient(135deg, #eef2ff, #dbeafe);
    color: #1e40af;
    font-weight: 800;
    font-size: 13px;
}

html[data-theme="dark"] .supervisor-dashboard-page .score-pill {
    background: rgba(59,130,246,.14);
    color: #93c5fd;
}

.supervisor-dashboard-page .table-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    padding: 9px 14px;
    background: linear-gradient(135deg, #1b00ff, #4338ca);
    color: #fff;
    text-decoration: none;
    font-weight: 700;
    font-size: 13px;
    box-shadow: 0 10px 20px rgba(27, 0, 255, 0.16);
}

.supervisor-dashboard-page .table-action-btn:hover {
    color: #fff;
    text-decoration: none;
    transform: translateY(-2px);
}

.supervisor-dashboard-page .empty-dashboard-state {
    padding: 55px 20px;
    text-align: center;
    color: var(--text-soft, #64748b);
}

.supervisor-dashboard-page .empty-dashboard-state i {
    font-size: 44px;
    color: color-mix(in srgb, var(--text-soft, #cbd5e1) 45%, transparent);
    margin-bottom: 14px;
}

.supervisor-dashboard-page .empty-dashboard-state h6 {
    font-size: 18px;
    color: var(--text, #0f172a);
    font-weight: 700;
    margin-bottom: 8px;
}

.supervisor-dashboard-page .custom-pagination-wrap {
    padding: 18px 20px 24px;
    border-top: 1px solid color-mix(in srgb, var(--border, #eef2f7) 80%, transparent);
    display: flex;
    justify-content: center;
    align-items: center;
}

.supervisor-dashboard-page .custom-pagination {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
}

.supervisor-dashboard-page .custom-page-item {
    min-width: 42px;
    height: 42px;
    border-radius: 12px;
    border: 1px solid var(--border, #e2e8f0);
    background: var(--surface, #fff);
    color: var(--text-soft, #334155);
    font-weight: 700;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 4px 14px rgba(15, 23, 42, 0.04);
    padding: 0 14px;
}

.supervisor-dashboard-page .custom-page-item:hover {
    text-decoration: none;
    color: var(--primary, #1b00ff);
    border-color: #c7d2fe;
    background: color-mix(in srgb, var(--primary, #1b00ff) 8%, transparent);
    transform: translateY(-2px);
    box-shadow: 0 10px 22px rgba(27, 0, 255, 0.10);
}

.supervisor-dashboard-page .custom-page-item.active {
    background: linear-gradient(135deg, #1b00ff, #4338ca);
    color: #fff;
    border-color: transparent;
    box-shadow: 0 12px 24px rgba(27, 0, 255, 0.22);
}

@media (max-width: 991px) {
    .supervisor-dashboard-page .hero-title {
        font-size: 28px;
    }

    .supervisor-dashboard-page .hero-summary-metrics,
    .supervisor-dashboard-page .legend-grid,
    .supervisor-dashboard-page .scan-metric-grid {
        grid-template-columns: 1fr;
    }

    .supervisor-dashboard-page .bar-chart-wrap {
        height: auto;
        align-items: stretch;
        flex-direction: column;
    }

    .supervisor-dashboard-page .bar-group {
        max-width: 100%;
    }

    .supervisor-dashboard-page .bar-track {
        height: 120px;
    }

    .supervisor-dashboard-page .announcement-title {
        font-size: 22px;
    }
}
</style>

<div class="pd-ltr-20 xs-pd-20-10 supervisor-dashboard-page">
    <div class="min-height-200px">

        <div class="super-hero-card mb-4">
            <div class="row align-items-center">
                <div class="col-xl-8 col-lg-7">
                    <div class="hero-content">
                        <div class="hero-eyebrow">{{ __('backend.supervisor_dashboard.hero_badge') }}</div>
                        <h2 class="hero-title">{{ __('backend.supervisor_dashboard.hero_title', ['name' => $user->name]) }}</h2>
                        <p class="hero-text">
                            {{ __('backend.supervisor_dashboard.hero_subtitle') }}
                        </p>

                        <div class="hero-actions">
                            <a href="{{ route('supervisor.projects.index') }}" class="hero-btn hero-btn-primary">
                                <i class="fa fa-folder-open mr-2"></i> {{ __('backend.supervisor_dashboard.my_projects') }}
                            </a>
                            <a href="{{ route('supervisor.projects.pending') }}" class="hero-btn hero-btn-outline">
                                <i class="fa fa-clock-o mr-2"></i> {{ __('backend.supervisor_dashboard.pending_reviews') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5 mt-4 mt-lg-0">
                    <div class="hero-summary-card">
                        <div class="hero-summary-head">
                            <span>{{ __('backend.supervisor_dashboard.review_performance') }}</span>
                            <span class="hero-summary-badge">
                                {{ $totalProjects }} {{ __('backend.supervisor_dashboard.projects') }}
                            </span>
                        </div>

                        <div class="hero-summary-score">
                            <div class="score-number">{{ $avgScanScore }}</div>
                            <div class="score-label">{{ __('backend.supervisor_dashboard.avg_scan_score') }}</div>
                        </div>

                        <div class="hero-summary-metrics">
                            <div class="metric-item">
                                <span>{{ __('backend.supervisor_dashboard.pending') }}</span>
                                <strong>{{ $pendingReviews }}</strong>
                            </div>
                            <div class="metric-item">
                                <span>{{ __('backend.supervisor_dashboard.approved') }}</span>
                                <strong>{{ $approvedProjects }}</strong>
                            </div>
                            <div class="metric-item">
                                <span>{{ __('backend.supervisor_dashboard.revisions') }}</span>
                                <strong>{{ $revisionRequests }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($featuredAnnouncement)
            <div class="announcement-main-card mb-4">
                <div class="announcement-main-content">
                    <div class="announcement-badges">
                        <span class="announcement-badge">
                            <i class="fa fa-bullhorn"></i>
                            {{ __('backend.supervisor_dashboard.announcement') }}
                        </span>

                        @if($featuredAnnouncement->is_pinned)
                            <span class="announcement-badge pinned">
                                <i class="fa fa-thumb-tack"></i>
                                {{ __('backend.supervisor_dashboard.pinned') }}
                            </span>
                        @endif

                        @if($featuredAnnouncement->expires_at)
                            <span class="announcement-badge expire">
                                <i class="fa fa-clock-o"></i>
                                {{ __('backend.supervisor_dashboard.until', ['date' => $featuredAnnouncement->expires_at->format('M d, Y • h:i A')]) }}
                            </span>
                        @endif
                    </div>

                    <div class="announcement-title">
                        {{ $featuredAnnouncement->title }}
                    </div>

                    <p class="announcement-text">
                        {{ $featuredAnnouncement->body }}
                    </p>
                </div>
            </div>

            @if($otherAnnouncements->count())
                <div class="row announcement-grid mb-4">
                    @foreach($otherAnnouncements as $announcement)
                        <div class="col-xl-4 col-md-6 mb-3">
                            <div class="announcement-mini-card">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                    <div class="announcement-mini-title">
                                        {{ $announcement->title }}
                                    </div>

                                    @if($announcement->is_pinned)
                                        <span class="announcement-badge pinned" style="padding: 6px 10px; font-size: 10px;">
                                            <i class="fa fa-thumb-tack"></i>
                                            {{ __('backend.supervisor_dashboard.pinned') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="announcement-mini-text">
                                    {{ \Illuminate\Support\Str::limit($announcement->body, 170) }}
                                </div>

                                @if($announcement->expires_at)
                                    <div class="announcement-mini-meta">
                                        {{ __('backend.supervisor_dashboard.visible_until', ['date' => $announcement->expires_at->format('M d, Y • h:i A')]) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-card kpi-primary">
                    <div class="kpi-icon">
                        <i class="fa fa-folder-open"></i>
                    </div>
                    <div class="kpi-info">
                        <div class="kpi-label">{{ __('backend.supervisor_dashboard.total_projects') }}</div>
                        <div class="kpi-value">{{ $totalProjects }}</div>
                        <div class="kpi-sub">{{ __('backend.supervisor_dashboard.total_projects_note') }}</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-card kpi-warning">
                    <div class="kpi-icon">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                    <div class="kpi-info">
                        <div class="kpi-label">{{ __('backend.supervisor_dashboard.pending_reviews') }}</div>
                        <div class="kpi-value">{{ $pendingReviews }}</div>
                        <div class="kpi-sub">{{ $pendingPercent }}%</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-card kpi-success">
                    <div class="kpi-icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="kpi-info">
                        <div class="kpi-label">{{ __('backend.supervisor_dashboard.approved_projects') }}</div>
                        <div class="kpi-value">{{ $approvedProjects }}</div>
                        <div class="kpi-sub">{{ $approvedPercent }}%</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="kpi-card kpi-danger">
                    <div class="kpi-icon">
                        <i class="fa fa-refresh"></i>
                    </div>
                    <div class="kpi-info">
                        <div class="kpi-label">{{ __('backend.supervisor_dashboard.revision_requests') }}</div>
                        <div class="kpi-value">{{ $revisionRequests }}</div>
                        <div class="kpi-sub">{{ $revisionPercent }}%</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-5 mb-3">
                <div class="dashboard-panel h-100">
                    <div class="panel-header">
                        <div>
                            <h5>{{ __('backend.supervisor_dashboard.review_distribution') }}</h5>
                            <p class="mb-0">{{ __('backend.supervisor_dashboard.review_distribution_subtitle') }}</p>
                        </div>
                    </div>

                    <div class="donut-wrap">
                        <div class="donut-chart"
                             style="background:
                                conic-gradient(
                                    #f59e0b 0% {{ $pendingPercent }}%,
                                    #22c55e {{ $pendingPercent }}% {{ $pendingPercent + $approvedPercent }}%,
                                    #ef4444 {{ $pendingPercent + $approvedPercent }}% 100%
                                );">
                            <div class="donut-inner">
                                <div class="donut-total">{{ $totalProjects }}</div>
                                <div class="donut-label">{{ __('backend.supervisor_dashboard.projects') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="legend-grid">
                        <div class="legend-item">
                            <span class="legend-dot legend-warning"></span>
                            <div>
                                <strong>{{ $pendingReviews }}</strong>
                                <small>{{ __('backend.supervisor_dashboard.pending') }}</small>
                            </div>
                        </div>

                        <div class="legend-item">
                            <span class="legend-dot legend-success"></span>
                            <div>
                                <strong>{{ $approvedProjects }}</strong>
                                <small>{{ __('backend.supervisor_dashboard.approved') }}</small>
                            </div>
                        </div>

                        <div class="legend-item">
                            <span class="legend-dot legend-danger"></span>
                            <div>
                                <strong>{{ $revisionRequests }}</strong>
                                <small>{{ __('backend.supervisor_dashboard.revisions') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7 mb-3">
                <div class="dashboard-panel h-100">
                    <div class="panel-header">
                        <div>
                            <h5>{{ __('backend.supervisor_dashboard.review_status_analytics') }}</h5>
                            <p class="mb-0">{{ __('backend.supervisor_dashboard.review_status_analytics_subtitle') }}</p>
                        </div>
                    </div>

                    <div class="bar-chart-wrap">
                        <div class="bar-group">
                            <div class="bar-value">{{ $chartPending }}</div>
                            <div class="bar-track">
                                <div class="bar-fill bar-fill-warning" style="height: {{ ($chartPending / $barMax) * 100 }}%;"></div>
                            </div>
                            <div class="bar-label">{{ __('backend.supervisor_dashboard.pending') }}</div>
                        </div>

                        <div class="bar-group">
                            <div class="bar-value">{{ $chartApproved }}</div>
                            <div class="bar-track">
                                <div class="bar-fill bar-fill-success" style="height: {{ ($chartApproved / $barMax) * 100 }}%;"></div>
                            </div>
                            <div class="bar-label">{{ __('backend.supervisor_dashboard.approved') }}</div>
                        </div>

                        <div class="bar-group">
                            <div class="bar-value">{{ $chartRevision }}</div>
                            <div class="bar-track">
                                <div class="bar-fill bar-fill-danger" style="height: {{ ($chartRevision / $barMax) * 100 }}%;"></div>
                            </div>
                            <div class="bar-label">{{ __('backend.supervisor_dashboard.revisions') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-6 mb-3">
                <div class="dashboard-panel h-100">
                    <div class="panel-header">
                        <div>
                            <h5>{{ __('backend.supervisor_dashboard.review_progress_breakdown') }}</h5>
                            <p class="mb-0">{{ __('backend.supervisor_dashboard.review_progress_breakdown_subtitle') }}</p>
                        </div>
                    </div>

                    <div class="progress-block">
                        <div class="progress-row">
                            <div class="progress-meta">
                                <span>{{ __('backend.supervisor_dashboard.pending_reviews_label') }}</span>
                                <strong>{{ $pendingPercent }}%</strong>
                            </div>
                            <div class="custom-progress">
                                <div class="custom-progress-bar warning" style="width: {{ $pendingPercent }}%;"></div>
                            </div>
                        </div>

                        <div class="progress-row">
                            <div class="progress-meta">
                                <span>{{ __('backend.supervisor_dashboard.approved_projects_label') }}</span>
                                <strong>{{ $approvedPercent }}%</strong>
                            </div>
                            <div class="custom-progress">
                                <div class="custom-progress-bar success" style="width: {{ $approvedPercent }}%;"></div>
                            </div>
                        </div>

                        <div class="progress-row">
                            <div class="progress-meta">
                                <span>{{ __('backend.supervisor_dashboard.revision_requests_label') }}</span>
                                <strong>{{ $revisionPercent }}%</strong>
                            </div>
                            <div class="custom-progress">
                                <div class="custom-progress-bar danger" style="width: {{ $revisionPercent }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 mb-3">
                <div class="dashboard-panel h-100">
                    <div class="panel-header">
                        <div>
                            <h5>{{ __('backend.supervisor_dashboard.scan_pipeline_snapshot') }}</h5>
                            <p class="mb-0">{{ __('backend.supervisor_dashboard.scan_pipeline_snapshot_subtitle') }}</p>
                        </div>
                    </div>

                    <div class="scan-metric-grid">
                        <div class="scan-metric-card">
                            <div class="scan-metric-icon scan-blue">
                                <i class="fa fa-chart-line"></i>
                            </div>
                            <div class="scan-metric-text">
                                <span>{{ __('backend.supervisor_dashboard.average_score') }}</span>
                                <strong>{{ $avgScanScore }}</strong>
                            </div>
                        </div>

                        <div class="scan-metric-card">
                            <div class="scan-metric-icon scan-green">
                                <i class="fa fa-check"></i>
                            </div>
                            <div class="scan-metric-text">
                                <span>{{ __('backend.supervisor_dashboard.completed_scans') }}</span>
                                <strong>{{ $completedScan }}</strong>
                            </div>
                        </div>

                        <div class="scan-metric-card">
                            <div class="scan-metric-icon scan-orange">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div class="scan-metric-text">
                                <span>{{ __('backend.supervisor_dashboard.pending_scans') }}</span>
                                <strong>{{ $pendingScan }}</strong>
                            </div>
                        </div>

                        <div class="scan-metric-card">
                            <div class="scan-metric-icon scan-red">
                                <i class="fa fa-times"></i>
                            </div>
                            <div class="scan-metric-text">
                                <span>{{ __('backend.supervisor_dashboard.failed_scans') }}</span>
                                <strong>{{ $failedScan }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-panel">
            <div class="panel-header">
                <div>
                    <h5>{{ __('backend.supervisor_dashboard.recent_projects') }}</h5>
                    <p class="mb-0">{{ __('backend.supervisor_dashboard.recent_projects_subtitle') }}</p>
                </div>
                <a href="{{ route('supervisor.projects.index') }}" class="panel-action-btn">
                    {{ __('backend.supervisor_dashboard.view_all') }}
                </a>
            </div>

            <div class="panel-body p-0">
                @if($latestProjects->count())
                    <div class="table-responsive" style="overflow-x: hidden;">
                        <table class="table dashboard-table mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">{{ __('backend.supervisor_dashboard.table_number') }}</th>
                                    <th style="width: 220px;">{{ __('backend.supervisor_dashboard.project') }}</th>
                                    <th style="width: 180px;">{{ __('backend.supervisor_dashboard.student') }}</th>
                                    <th style="width: 140px;">{{ __('backend.supervisor_dashboard.status') }}</th>
                                    <th style="width: 130px;">{{ __('backend.supervisor_dashboard.scanner') }}</th>
                                    <th style="width: 100px;">{{ __('backend.supervisor_dashboard.score') }}</th>
                                    <th style="width: 130px;">{{ __('backend.supervisor_dashboard.updated') }}</th>
                                    <th class="text-end" style="width: 110px;">{{ __('backend.supervisor_dashboard.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestProjects as $project)
                                    @php
                                        $statusClass = match(strtolower($project->status ?? '')) {
                                            'pending', 'scan_requested', 'awaiting_manual_review' => 'badge-soft-warning',
                                            'approved', 'active', 'published' => 'badge-soft-primary',
                                            'completed' => 'badge-soft-success',
                                            'rejected', 'scan_failed', 'failed' => 'badge-soft-danger',
                                            default => 'badge-soft-secondary',
                                        };

                                        $scanClass = match(strtolower($project->scanner_status ?? '')) {
                                            'completed' => 'badge-soft-success',
                                            'pending' => 'badge-soft-warning',
                                            'failed' => 'badge-soft-danger',
                                            default => 'badge-soft-secondary',
                                        };
                                    @endphp

                                    <tr>
                                        <td>{{ $project->project_id }}</td>
                                        <td>
                                            <div class="project-cell-title">
                                                {{ $project->name ?? __('backend.supervisor_dashboard.untitled_project') }}
                                            </div>
                                            <div class="project-cell-sub">
                                                {{ $project->category ?? __('backend.supervisor_dashboard.no_category') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="project-cell-title">
                                                {{ $project->student->name ?? __('backend.supervisor_dashboard.not_available') }}
                                            </div>
                                            <div class="project-cell-sub">
                                                {{ $project->student->email ?? __('backend.supervisor_dashboard.no_email') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge-soft {{ $statusClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $project->status ?? __('backend.supervisor_dashboard.not_available'))) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-soft {{ $scanClass }}">
                                                {{ ucfirst(str_replace('_', ' ', $project->scanner_status ?? __('backend.supervisor_dashboard.not_available'))) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="score-pill">{{ $project->scan_score ?? __('backend.supervisor_dashboard.empty_score') }}</div>
                                        </td>
                                        <td>
                                            <div class="project-cell-title">
                                                {{ optional($project->updated_at)->format('Y-m-d') ?? __('backend.supervisor_dashboard.empty_date') }}
                                            </div>
                                            <div class="project-cell-sub">
                                                {{ optional($project->updated_at)->format('h:i A') ?? '' }}
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('supervisor.projects.show', $project->project_id) }}" class="table-action-btn">
                                                {{ __('backend.supervisor_dashboard.open') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($latestProjects instanceof \Illuminate\Pagination\LengthAwarePaginator && $latestProjects->lastPage() > 1)
                        <div class="custom-pagination-wrap">
                            <div class="custom-pagination">
                                @for($i = 1; $i <= $latestProjects->lastPage(); $i++)
                                    <a href="{{ $latestProjects->url($i) }}"
                                       class="custom-page-item {{ $latestProjects->currentPage() == $i ? 'active' : '' }}">
                                        {{ $i }}
                                    </a>
                                @endfor
                            </div>
                        </div>
                    @endif
                @else
                    <div class="empty-dashboard-state">
                        <i class="fa fa-folder-open-o"></i>
                        <h6>{{ __('backend.supervisor_dashboard.no_projects_available') }}</h6>
                        <p class="mb-0">{{ __('backend.supervisor_dashboard.no_projects_available_text') }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection