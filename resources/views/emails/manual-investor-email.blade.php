<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subjectLine }}</title>
</head>
<body style="font-family: Arial, sans-serif; color:#1f2937; line-height:1.7; background:#f8fafc; padding:30px;">
    <div style="max-width: 700px; margin: 0 auto; background:#ffffff; border:1px solid #e5e7eb; border-radius:16px; padding:30px;">
        <h2 style="margin-top:0; margin-bottom:18px; color:#111827;">{{ $subjectLine }}</h2>

        <div style="white-space: pre-line; color:#374151; font-size:15px;">
            {{ $messageBody }}
        </div>

        <hr style="border:none; border-top:1px solid #e5e7eb; margin:24px 0;">

        <div style="font-size:12px; color:#6b7280;">
            {{ __('backend.mail_manager_panel.sent_from_manager_panel') }}
        </div>
    </div>
</body>
</html>