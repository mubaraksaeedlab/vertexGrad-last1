<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ __('backend.mail_scheduled_report_ready.title') }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1e293b; line-height: 1.8;">
    <h2 style="margin-bottom: 10px;">{{ __('backend.mail_scheduled_report_ready.heading') }}</h2>

    <p>{{ __('backend.mail_scheduled_report_ready.generated_successfully') }}</p>

    <p><strong>{{ __('backend.mail_scheduled_report_ready.report_name') }}</strong> {{ $reportName }}</p>
    <p><strong>{{ __('backend.mail_scheduled_report_ready.frequency') }}</strong> {{ ucfirst($frequency) }}</p>
    <p><strong>{{ __('backend.mail_scheduled_report_ready.generated_at') }}</strong> {{ $generatedAt }}</p>

    <p>{{ __('backend.mail_scheduled_report_ready.attachment_notice') }}</p>

    <p>{{ __('backend.mail_scheduled_report_ready.regards') }}<br>{{ __('backend.mail_scheduled_report_ready.system_name') }}</p>
</body>
</html>