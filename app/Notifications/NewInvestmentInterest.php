<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewInvestmentInterest extends Notification
{
    use Queueable;

    protected Project $project;
    protected string $investorName;

    public function __construct(Project $project, $investorName)
    {
        $this->project = $project;
        $this->investorName = $investorName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'key' => 'new_investment_interest',
            'project_id' => $this->project->project_id,
            'project_name' => $this->project->name,
            'investor_name' => $this->investorName,
            'url' => route('dashboard.academic', [], false),
            'icon' => 'fas fa-hand-holding-usd',
            'type' => 'new_investment_interest',
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}