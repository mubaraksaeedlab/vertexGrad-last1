<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FinalDecisionNotification extends Notification
{
    use Queueable;

    protected Project $project;
    protected string $decision;
    protected ?string $managerNote;

    public function __construct(Project $project, string $decision, ?string $managerNote = null)
    {
        $this->project = $project;
        $this->decision = $decision;
        $this->managerNote = $managerNote;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

public function toDatabase(object $notifiable): array
{
    $notificationKey = match ($this->decision) {
        'published' => 'project_published',
        'revision'  => 'project_revision',
        'rejected'  => 'project_rejected',
        default     => 'project_final_decision',
    };

    return [
        'key' => $notificationKey,
        'project_id' => $this->project->project_id,
        'project_title' => $this->project->title ?? $this->project->name ?? 'Project',
        'decision' => $this->decision,
        'manager_note' => $this->managerNote,
        'url' => route('frontend.projects.show', $this->project, false),
        'icon' => 'gavel',
        'type' => 'final_decision',
    ];
}

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}