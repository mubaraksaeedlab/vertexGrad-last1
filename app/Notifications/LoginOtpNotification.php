<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $code)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('mail.login_otp.subject'))
            ->greeting(__('mail.login_otp.greeting', ['name' => $notifiable->name]))
            ->line(__('mail.login_otp.line_1'))
            ->line($this->code)
            ->line(__('mail.login_otp.line_2'))
            ->line(__('mail.login_otp.line_3'));
    }
}