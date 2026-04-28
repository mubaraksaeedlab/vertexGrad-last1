<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    protected array $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'key' => $this->details['key'] ?? null,

            'title' => $this->details['title'] ?? null,
            'message' => $this->details['message'] ?? null,

            'url' => $this->details['url'] ?? '#',
            'icon' => $this->details['icon'] ?? 'fas fa-info-circle',
            'type' => $this->details['type'] ?? 'general',

            'project_id' => $this->details['project_id'] ?? null,
            'project_name' => $this->details['project_name'] ?? null,
            'user_name' => $this->details['user_name'] ?? null,
            'amount' => $this->details['amount'] ?? null,
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}