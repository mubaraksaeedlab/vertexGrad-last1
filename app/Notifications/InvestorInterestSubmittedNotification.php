<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvestorInterestSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public $project, public $investor)
    {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'key' => 'investor_interest_submitted',
            'project_id' => $this->project->project_id,
            'project_name' => $this->project->name,
            'investor_id' => $this->investor->id,
            'investor_name' => $this->investor->name,
            'url' => route('admin.projects.show', ['project' => $this->project->project_id], false),
            'icon' => 'fas fa-handshake',
            'type' => 'investor_interest_submitted',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}