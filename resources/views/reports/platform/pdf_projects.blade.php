<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('backend.projects_report_pdf.title') }}</title>
    <style>
        /* الخط العربي الافتراضي في DomPDF */
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2 {
            color: #1b00ff;
            text-align: center;
            margin-bottom: 10px;
        }

        p.total {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        /* ألوان الحالة */
        .status-active { background-color: #d1ecf1; color: #0c5460; font-weight: bold; }
        .status-pending { background-color: #fff3cd; color: #856404; font-weight: bold; }
        .status-completed { background-color: #d4edda; color: #155724; font-weight: bold; }

    </style>
</head>
<body>

    <h2>{{ __('backend.projects_report_pdf.heading') }}</h2>
    <p class="total">{{ __('backend.projects_report_pdf.total_projects') }}: {{ $projects->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('backend.projects_report_pdf.project_name') }}</th>
                <th>{{ __('backend.projects_report_pdf.student') }}</th>
                <th>{{ __('backend.projects_report_pdf.supervisor') }}</th>
                <th>{{ __('backend.projects_report_pdf.manager') }}</th>
                <th>{{ __('backend.projects_report_pdf.investor') }}</th>
                <th>{{ __('backend.projects_report_pdf.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->student->name ?? __('backend.projects_report_pdf.empty') }}</td>
                    <td>{{ $project->supervisor->name ?? __('backend.projects_report_pdf.empty') }}</td>
                    <td>{{ $project->manager->name ?? __('backend.projects_report_pdf.empty') }}</td>
                    <td>{{ $project->investor->name ?? __('backend.projects_report_pdf.empty') }}</td>
                    <td class="
                        @if(strtolower($project->status) == 'active') status-active
                        @elseif(strtolower($project->status) == 'pending') status-pending
                        @elseif(strtolower($project->status) == 'completed') status-completed
                        @else '' @endif">
                        {{ $project->status }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>