<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('backend.mail_contact_reply.title') }}</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 30px; color: #0f172a;">
    <div style="max-width: 700px; margin: 0 auto; background: #ffffff; border-radius: 14px; padding: 30px; border: 1px solid #e2e8f0;">
        <h2 style="margin-top: 0; color: #1d4ed8;">{{ __('backend.mail_contact_reply.heading') }}</h2>

        <p>{{ __('backend.mail_contact_reply.hello') }} {{ $contactMessage->name }},</p>

        <p>{{ __('backend.mail_contact_reply.intro') }}</p>

        <div style="margin: 20px 0; padding: 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; white-space: pre-line; line-height: 1.8;">
            {{ $reply->reply_message }}
        </div>

        <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 24px 0;">

        <p style="margin-bottom: 6px;"><strong>{{ __('backend.mail_contact_reply.original_subject') }}</strong> {{ $contactMessage->subject_label }}</p>
        <p style="margin-top: 0;"><strong>{{ __('backend.mail_contact_reply.original_message') }}</strong></p>

        <div style="padding: 14px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; white-space: pre-line; color: #475569;">
            {{ $contactMessage->message }}
        </div>

        <p style="margin-top: 24px;">{{ __('backend.mail_contact_reply.best_regards') }}<br>{{ __('backend.mail_contact_reply.team_name') }}</p>
    </div>
</body>
</html>