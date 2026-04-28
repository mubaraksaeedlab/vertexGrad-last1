<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('backend.platform_report_pdf.page_title') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

<h2>{{ __('backend.platform_report_pdf.heading') }}</h2>

{{-- Investors --}}
<h3>{{ __('backend.platform_report_pdf.investors_heading', ['count' => count($investors)]) }}</h3>
<table>
    <thead>
        <tr>
            <th>{{ __('backend.platform_report_pdf.table_number') }}</th>
            <th>{{ __('backend.platform_report_pdf.name') }}</th>
            <th>{{ __('backend.platform_report_pdf.email') }}</th>
            <th>{{ __('backend.platform_report_pdf.status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($investors as $i => $inv)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $inv->name }}</td>
            <td>{{ $inv->email }}</td>
            <td>{{ ucfirst($inv->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="page-break"></div>

{{-- Students --}}
<h3>{{ __('backend.platform_report_pdf.students_heading', ['count' => count($students)]) }}</h3>
<table>
    <thead>
        <tr>
            <th>{{ __('backend.platform_report_pdf.table_number') }}</th>
            <th>{{ __('backend.platform_report_pdf.name') }}</th>
            <th>{{ __('backend.platform_report_pdf.email') }}</th>
            <th>{{ __('backend.platform_report_pdf.major') }}</th>
            <th>{{ __('backend.platform_report_pdf.status') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $i => $stu)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $stu->name }}</td>
            <td>{{ $stu->email }}</td>
            <td>{{ $stu->student->major ?? __('backend.platform_report_pdf.empty_dash') }}</td>
            <td>{{ ucfirst($stu->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="page-break"></div>

{{-- Projects --}}
<h3>{{ __('backend.platform_report_pdf.projects_heading', ['count' => count($projects)]) }}</h3>
<table>
    <thead>
        <tr>
            <th>{{ __('backend.platform_report_pdf.table_number') }}</th>
            <th>{{ __('backend.platform_report_pdf.project_name') }}</th>
            <th>{{ __('backend.platform_report_pdf.student') }}</th>
            <th>{{ __('backend.platform_report_pdf.status') }}</th>
            <th>{{ __('backend.platform_report_pdf.budget') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($projects as $i => $pro)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $pro->name }}</td>
            <td>{{ $pro->student->name ?? __('backend.platform_report_pdf.empty_dash') }}</td>
            <td>{{ $pro->status }}</td>
            <td>{{ $pro->budget ?? '0' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>