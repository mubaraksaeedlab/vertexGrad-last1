<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuspiciousLoginAlertNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $ip,
        public string $browser,
        public string $os,
        public string $device
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('mail.suspicious_login.subject'))
            ->greeting(__('mail.suspicious_login.greeting', ['name' => $notifiable->name]))
            ->line(__('mail.suspicious_login.line_1'))
            ->line(__('mail.suspicious_login.ip', ['ip' => $this->ip]))
            ->line(__('mail.suspicious_login.browser', ['browser' => $this->browser]))
            ->line(__('mail.suspicious_login.os', ['os' => $this->os]))
            ->line(__('mail.suspicious_login.device', ['device' => $this->device]))
            ->line(__('mail.suspicious_login.line_2'))
            ->line(__('mail.suspicious_login.line_3'));
    }
}