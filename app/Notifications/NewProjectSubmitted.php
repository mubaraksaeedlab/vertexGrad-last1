<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewProjectSubmitted extends Notification
{
    use Queueable;

    protected Project $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'key' => 'new_project_submitted',
            'project_id' => $this->project->project_id,
            'project_name' => $this->project->name,
            'url' => route('manager.dashboard', [], false),
            'icon' => 'fas fa-file-import',
            'type' => 'new_project_submitted',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}