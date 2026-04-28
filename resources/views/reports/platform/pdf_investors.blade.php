<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('backend.investors_report_pdf.title') }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>{{ __('backend.investors_report_pdf.heading') }}</h2>
    <p>{{ __('backend.investors_report_pdf.total_investors') }}: {{ $investors->count() }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('backend.investors_report_pdf.name') }}</th>
                <th>{{ __('backend.investors_report_pdf.email') }}</th>
                <th>{{ __('backend.investors_report_pdf.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($investors as $investor)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $investor->name }}</td>
                <td>{{ $investor->email }}</td>
                <td>{{ $investor->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>