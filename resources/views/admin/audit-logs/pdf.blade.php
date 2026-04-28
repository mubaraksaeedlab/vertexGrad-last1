<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('backend.audit_report.title') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #0f172a;
            margin: 28px;
            font-size: 12px;
        }

        .report-header {
            border-radius: 20px;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%);
            color: #fff;
            padding: 24px 28px;
            margin-bottom: 18px;
        }

        .report-header h1 {
            margin: 0 0 6px;
            font-size: 28px;
            font-weight: 800;
        }

        .report-header p {
            margin: 0;
            font-size: 13px;
            color: rgba(255,255,255,0.85);
        }

        .meta-grid {
            display: table;
            width: 100%;
            margin: 18px 0;
        }

        .meta-box {
            display: table-cell;
            width: 33.33%;
            padding: 12px 14px;
            border: 1px solid #dbe4ee;
            background: #f8fafc;
            border-radius: 12px;
        }

        .meta-title {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-weight: 700;
        }

        .meta-value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .filters-box {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 16px;
        }

        .filters-box strong {
            display: block;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        thead th {
            background: #eff6ff;
            color: #0f172a;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 800;
            padding: 10px 8px;
            border: 1px solid #dbe4ee;
        }

        tbody td {
            padding: 9px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
            word-wrap: break-word;
            font-size: 11px;
        }

        .muted {
            color: #64748b;
            font-size: 10px;
        }

        .footer {
            margin-top: 16px;
            text-align: right;
            color: #94a3b8;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="report-header">
        <h1>{{ __('backend.audit_report.header_title') }}</h1>
        <p>{{ __('backend.audit_report.header_subtitle') }}</p>
    </div>

    <div class="meta-grid">
        <div class="meta-box">
            <div class="meta-title">{{ __('backend.audit_report.generated_date') }}</div>
            <div class="meta-value">{{ $generatedAt->format('Y-m-d') }}</div>
        </div>
        <div class="meta-box">
            <div class="meta-title">{{ __('backend.audit_report.generated_time') }}</div>
            <div class="meta-value">{{ $generatedAt->format('h:i:s A') }}</div>
        </div>
        <div class="meta-box">
            <div class="meta-title">{{ __('backend.audit_report.total_records') }}</div>
            <div class="meta-value">{{ $logs->count() }}</div>
        </div>
    </div>

    <div class="filters-box">
        <strong>{{ __('backend.audit_report.applied_filters') }}</strong>
        {{ __('backend.audit_report.search') }}: {{ $filters['search'] ?? '—' }} |
        {{ __('backend.audit_report.user') }}: {{ $filters['user'] ?? '—' }} |
        {{ __('backend.audit_report.event') }}: {{ $filters['event'] ?? '—' }} |
        {{ __('backend.audit_report.category') }}: {{ $filters['category'] ?? '—' }} |
        {{ __('backend.audit_report.from') }}: {{ $filters['from'] ?? '—' }} |
        {{ __('backend.audit_report.to') }}: {{ $filters['to'] ?? '—' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%;">#</th>
                <th style="width:11%;">{{ __('backend.audit_report.user') }}</th>
                <th style="width:8%;">{{ __('backend.audit_report.event') }}</th>
                <th style="width:10%;">{{ __('backend.audit_report.category') }}</th>
                <th style="width:12%;">{{ __('backend.audit_report.subject') }}</th>
                <th style="width:27%;">{{ __('backend.audit_report.description') }}</th>
                <th style="width:8%;">{{ __('backend.audit_report.ip') }}</th>
                <th style="width:10%;">{{ __('backend.audit_report.date') }}</th>
                <th style="width:10%;">{{ __('backend.audit_report.type') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->id }}</td>
                    <td>
                        <strong>{{ $log->user_name ?? __('backend.audit_report.system') }}</strong><br>
                        <span class="muted">{{ $log->user_type ?? '—' }}</span>
                    </td>
                    <td>{{ $log->event_label ?? ucfirst($log->event) }}</td>
                    <td>{{ $log->category ?? '—' }}</td>
                    <td>{{ $log->subject_title ?? '—' }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address ?? '—' }}</td>
                    <td>{{ optional($log->created_at)->format('Y-m-d h:i A') }}</td>
                    <td>{{ class_basename($log->subject_type ?? '—') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center;">{{ __('backend.audit_report.no_audit_records_found') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        {{ __('backend.audit_report.footer_prefix') }} {{ $generatedAt->format('Y-m-d h:i:s A') }}
    </div>

</body>
</html>