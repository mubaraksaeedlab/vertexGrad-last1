@extends('layouts.app')

@section('title', __('backend.platform_reports.page_title'))

@section('content')
<div class="container-fluid reports-page">
    <div class="page-header-card">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
            <div>
                <h1 class="page-title">{{ __('backend.platform_reports.heading') }}</h1>
                <p class="page-subtitle">
                    {{ __('backend.platform_reports.subtitle') }}
                </p>
            </div>
        </div>
    </div>

    <div class="row g-3 reports-grid">
        <div class="col-lg-4 col-md-6">
            <div class="report-card report-investors">
                <div class="report-top">
                    <div>
                        <p class="report-label">{{ __('backend.platform_reports.investors') }}</p>
                        <h3 class="report-value">{{ $investors->count() }}</h3>
                        <div class="report-note">{{ __('backend.platform_reports.investors_note') }}</div>
                    </div>
                    <span class="report-icon">👥</span>
                </div>

                <div class="report-actions">
                    <a href="{{ route('admin.reports.investors.excel') }}" class="report-btn">{{ __('backend.platform_reports.excel') }}</a>
                    <a href="{{ route('admin.reports.investors.pdf') }}" class="report-btn">{{ __('backend.platform_reports.pdf') }}</a>
                    <button onclick="printReport('investors')" class="report-btn">{{ __('backend.platform_reports.print') }}</button>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="report-card report-students">
                <div class="report-top">
                    <div>
                        <p class="report-label">{{ __('backend.platform_reports.students') }}</p>
                        <h3 class="report-value">{{ $students->count() }}</h3>
                        <div class="report-note">{{ __('backend.platform_reports.students_note') }}</div>
                    </div>
                    <span class="report-icon">🎓</span>
                </div>

                <div class="report-actions">
                    <a href="{{ route('admin.reports.students.excel') }}" class="report-btn">{{ __('backend.platform_reports.excel') }}</a>
                    <a href="{{ route('admin.reports.students.pdf') }}" class="report-btn">{{ __('backend.platform_reports.pdf') }}</a>
                    <button onclick="printReport('students')" class="report-btn">{{ __('backend.platform_reports.print') }}</button>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="report-card report-projects">
                <div class="report-top">
                    <div>
                        <p class="report-label">{{ __('backend.platform_reports.projects') }}</p>
                        <h3 class="report-value">{{ $projects->count() }}</h3>
                        <div class="report-note">{{ __('backend.platform_reports.projects_note') }}</div>
                    </div>
                    <span class="report-icon">🚀</span>
                </div>

                <div class="report-actions">
                    <a href="{{ route('admin.reports.projects.excel') }}" class="report-btn">{{ __('backend.platform_reports.excel') }}</a>
                    <a href="{{ route('admin.reports.projects.pdf') }}" class="report-btn">{{ __('backend.platform_reports.pdf') }}</a>
                    <button onclick="printReport('projects')" class="report-btn">{{ __('backend.platform_reports.print') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --page-bg: #f5f7fb;
        --card-bg: #ffffff;
        --text-main: #172033;
        --text-soft: #7b8497;
        --border-color: #e8ecf4;
        --shadow-sm: 0 8px 20px rgba(18, 38, 63, 0.06);
        --shadow-md: 0 14px 36px rgba(18, 38, 63, 0.10);
        --radius-xl: 24px;
    }

    body {
        background: var(--page-bg);
    }

    .reports-page {
        padding: 10px 0 24px;
    }

    .page-header-card {
        background: linear-gradient(135deg, #ffffff 0%, #f9fbff 100%);
        border: 1px solid var(--border-color);
        border-radius: var(--radius-xl);
        padding: 26px 28px;
        box-shadow: var(--shadow-sm);
        margin-bottom: 24px;
    }

    .page-title {
        margin: 0;
        font-size: 1.65rem;
        font-weight: 800;
        color: var(--text-main);
    }

    .page-subtitle {
        margin: 8px 0 0;
        color: var(--text-soft);
        font-size: 0.96rem;
    }

    .report-card {
        position: relative;
        overflow: hidden;
        width: 100%;
        min-height: 220px;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        background: var(--card-bg);
        padding: 22px 20px;
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .report-card::after {
        content: "";
        position: absolute;
        top: -35px;
        right: -35px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        opacity: 0.08;
        background: currentColor;
    }

    .report-investors {
        color: #1d4ed8;
        background: linear-gradient(135deg, #ffffff 0%, #eef6ff 100%);
    }

    .report-students {
        color: #15803d;
        background: linear-gradient(135deg, #ffffff 0%, #effcf7 100%);
    }

    .report-projects {
        color: #c2410c;
        background: linear-gradient(135deg, #ffffff 0%, #fff7ed 100%);
    }

    .report-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .report-label {
        margin: 0;
        color: #64748b;
        font-size: 0.92rem;
        font-weight: 700;
    }

    .report-value {
        margin: 14px 0 8px;
        font-size: 2rem;
        line-height: 1;
        font-weight: 800;
        color: #172033;
    }

    .report-note {
        color: #64748b;
        font-size: 0.84rem;
    }

    .report-icon {
        font-size: 1.7rem;
        line-height: 1;
    }

    .report-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .report-btn {
        min-height: 42px;
        border-radius: 12px;
        font-weight: 700;
        padding: 10px 16px;
        background: #ffffff;
        color: #172033;
        border: 1px solid #dbe4f0;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
    }

    .report-btn:hover {
        text-decoration: none;
        color: #172033;
        transform: translateY(-1px);
        background: #f8fafc;
    }

    @media(max-width:768px){
        .page-title {
            font-size: 1.3rem;
        }
    }
</style>

<script>
function printReport(type){
    let html = `<html><head><title>{{ __('backend.platform_reports.print_report_title') }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding:20px; color:#172033; }
        h1 { text-align:center; margin-bottom:10px; color:#172033; }
        p.total { font-weight:bold; margin-bottom:20px; font-size:16px; text-align:center; color:#475569; }
        table { width:100%; border-collapse: collapse; margin-top:10px; }
        th, td { border:1px solid #dbe4f0; padding:8px; text-align:left; }
        th { background-color:#172033; color:#fff; }
        tr:nth-child(even) { background-color:#f8fafc; }
    </style>
    </head><body>`;

    if(type==='investors'){
        html += `<h1>{{ __('backend.platform_reports.investors_report') }}</h1>`;
        html += `<p class="total">{{ __('backend.platform_reports.total_investors') }}: {{ $investors->count() }}</p>`;
        html += `<table><thead><tr><th>#</th><th>{{ __('backend.platform_reports.name') }}</th><th>{{ __('backend.platform_reports.email') }}</th><th>{{ __('backend.platform_reports.status') }}</th></tr></thead><tbody>`;
        @foreach($investors as $investor)
        html += `<tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $investor->name }}</td>
            <td>{{ $investor->email }}</td>
            <td>{{ $investor->status }}</td>
        </tr>`;
        @endforeach
        html += `</tbody></table>`;
    }
    else if(type==='students'){
        html += `<h1>{{ __('backend.platform_reports.students_report') }}</h1>`;
        html += `<p class="total">{{ __('backend.platform_reports.total_students') }}: {{ $students->count() }}</p>`;
        html += `<table><thead><tr><th>#</th><th>{{ __('backend.platform_reports.name') }}</th><th>{{ __('backend.platform_reports.email') }}</th><th>{{ __('backend.platform_reports.major') }}</th><th>{{ __('backend.platform_reports.status') }}</th></tr></thead><tbody>`;
        @foreach($students as $student)
        html += `<tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $student->name }}</td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->student->major ?? __('backend.platform_reports.empty') }}</td>
            <td>{{ $student->status }}</td>
        </tr>`;
        @endforeach
        html += `</tbody></table>`;
    }
    else if(type==='projects'){
        html += `<h1>{{ __('backend.platform_reports.projects_report') }}</h1>`;
        html += `<p class="total">{{ __('backend.platform_reports.total_projects') }}: {{ $projects->count() }}</p>`;
        html += `<table><thead><tr>
            <th>#</th><th>{{ __('backend.platform_reports.project_name') }}</th><th>{{ __('backend.platform_reports.student') }}</th><th>{{ __('backend.platform_reports.supervisor') }}</th>
            <th>{{ __('backend.platform_reports.manager') }}</th><th>{{ __('backend.platform_reports.investor') }}</th><th>{{ __('backend.platform_reports.status') }}</th>
        </tr></thead><tbody>`;
        @foreach($projects as $project)
        html += `<tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $project->name }}</td>
            <td>{{ $project->student->name ?? __('backend.platform_reports.empty') }}</td>
            <td>{{ $project->supervisor->name ?? __('backend.platform_reports.empty') }}</td>
            <td>{{ $project->manager->name ?? __('backend.platform_reports.empty') }}</td>
            <td>{{ $project->investor->name ?? __('backend.platform_reports.empty') }}</td>
            <td>{{ $project->status }}</td>
        </tr>`;
        @endforeach
        html += `</tbody></table>`;
    }

    html += `</body></html>`;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(html);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.page-header-card, .report-card').forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(10px)';

        setTimeout(() => {
            card.style.transition = 'all 0.35s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 70 * (index + 1));
    });
});
</script>
@endsection