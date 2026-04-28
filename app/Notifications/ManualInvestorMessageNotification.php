<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ManualInvestorMessageNotification extends Notification
{
    use Queueable;

    protected string $title;
    protected string $message;
    protected ?string $url;
    protected ?string $icon;
    protected ?string $key;

    public function __construct(string $title, string $message, ?string $url = null, ?string $icon = null, ?string $key = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
        $this->icon = $icon ?: 'fas fa-envelope';
        $this->key = $key;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'key' => $this->key,
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url ?: '#',
            'icon' => $this->icon,
            'type' => 'manual_message',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}