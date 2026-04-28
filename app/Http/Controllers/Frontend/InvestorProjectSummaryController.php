<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\PitchDeck\PitchDeckDataBuilder;
use App\Services\PitchDeck\PitchDeckGenerator;

class InvestorProjectSummaryController extends Controller
{
    public function show(Project $project, PitchDeckDataBuilder $builder)
    {
        $this->ensureInvestorVisible($project);

        $summary = $builder->build($project);
        $latestDeck = $project->latestPitchDeck;

        return view('frontend.projects.investor-summary', compact('project', 'summary', 'latestDeck'));
    }

    public function download(Project $project, PitchDeckGenerator $generator)
    {
        $this->ensureInvestorVisible($project);

        $latestDeck = $project->latestPitchDeck;

        if (
            !$latestDeck ||
            $latestDeck->status !== 'generated' ||
            !$latestDeck->pptx_path ||
            !file_exists(storage_path('app/' . $latestDeck->pptx_path))
        ) {
            $latestDeck = $generator->generate($project, auth('web')->id());
        }

        return response()->download(
            storage_path('app/' . $latestDeck->pptx_path),
            'project_' . $project->project_id . '_pitch_deck.pptx'
        );
    }

    private function ensureInvestorVisible(Project $project): void
    {
        $allowedStatuses = [
            'active',
            'published',
            'approved',
            'completed',
            'investor_visible',
        ];

        abort_unless(
            in_array($project->status, $allowedStatuses, true),
            403,
            'This project is not visible to investors.'
        );
    }
}