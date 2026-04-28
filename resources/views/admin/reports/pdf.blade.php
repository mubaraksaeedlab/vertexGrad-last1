<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ $report['title'] }}</title>
    <style>
        body {
            font-family: Tahoma, Arial, sans-serif;
            font-size: 13px;
            color: #111827;
            direction: rtl;
            text-align: right;
            margin: 24px;
        }

        h1, h2, h3, h4 {
            margin: 0 0 10px 0;
        }

        .header {
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #dbeafe;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 13px;
            color: #475569;
        }

        .summary-box {
            margin: 18px 0 20px;
            padding: 14px 16px;
            border: 1px solid #dbe4ee;
            border-radius: 12px;
            background: #f8fbff;
        }

        .summary-item {
            margin-bottom: 6px;
            font-size: 13px;
            color: #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            vertical-align: top;
            font-size: 12px;
        }

        th {
            background: #eff6ff;
            color: #0f172a;
            font-weight: 700;
        }

        tr:nth-child(even) td {
            background: #fafcff;
        }

        .ltr {
            direction: ltr;
            text-align: left;
        }

        .muted {
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $report['title'] }}</div>
        <div class="subtitle">
            {{ __('backend.report_pdf.period') }}: {{ $report['from']->format('Y-m-d') }} {{ __('backend.report_pdf.to') }} {{ $report['to']->format('Y-m-d') }}
        </div>
    </div>

    @if(!empty($report['summary']))
        <div class="summary-box">
            @foreach($report['summary'] as $label => $value)
                <div class="summary-item">
                    <strong>{{ $label }}:</strong> {{ $value }}
                </div>
            @endforeach
        </div>
    @endif

    <table>
        <thead>
            <tr>
                @foreach($report['headings'] as $heading)
                    <th>{{ ucwords(str_replace('_', ' ', $heading)) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($report['rows'] as $row)
                <tr>
                    @foreach($row as $value)
                        <td>{{ $value !== null && $value !== '' ? $value : '-' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ max(count($report['headings']), 1) }}" class="muted">
                        {{ __('backend.report_pdf.no_data_available') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>