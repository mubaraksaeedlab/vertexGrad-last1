<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProjectRevisionRequested extends Notification
{
    use Queueable;

    protected Project $project;
    protected string $reason;

    public function __construct(Project $project, $reason = '')
    {
        $this->project = $project;
        $this->reason = (string) $reason;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'key' => 'project_revision_requested',
            'project_id' => $this->project->project_id,
            'project_name' => $this->project->name,
            'reason' => $this->reason,
            'url' => route('dashboard.academic', [], false),
            'icon' => 'fas fa-exclamation-triangle',
            'type' => 'project_revision_requested',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}