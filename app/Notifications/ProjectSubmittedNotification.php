<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(public $project)
    {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'key' => 'project_submitted',
            'project_id' => $this->project->project_id,
            'project_name' => $this->project->name,
            'url' => route('admin.projects.show', ['project' => $this->project->project_id], false),
            'icon' => 'fas fa-file-upload',
            'type' => 'project_submitted',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}