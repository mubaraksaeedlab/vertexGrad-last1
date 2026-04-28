<?php

namespace App\Services\PitchDeck;

use App\Models\Project;

class PitchDeckDataBuilder
{
    public function build(Project $project): array
    {
        $project->loadMissing([
            'student',
            'supervisor',
            'manager',
            'reviews.supervisor',
            'files',
            'approvedInvestments',
            'projectCategory',
        ]);

        return [
            'project_id' => $project->project_id,
            'title' => $project->name,
            'description' => $project->description,

            'category' => $project->projectCategory?->display_name ?? $project->category,
            'category_slug' => $project->projectCategory?->slug,
            'deck_theme' => $project->projectCategory?->deck_theme ?? 'default',
            'accent_color' => $project->projectCategory?->accent_color,

            'project_type' => $project->project_type,
            'project_nature' => $project->project_nature,

            'student_name' => $project->student_name ?: optional($project->student)->name,
            'academic_level' => $project->academic_level,
            'supervisor_name' => $project->supervisor_name ?: optional($project->supervisor)->name,
            'supervisor_title' => $project->supervisor_title,
            'university_name' => $project->university_name,
            'college_name' => $project->college_name,
            'department' => $project->department,
            'governorate' => $project->governorate,

            'problem_statement' => $project->problem_statement,
            'target_beneficiaries' => $project->target_beneficiaries,
            'expected_impact' => $project->expected_impact,
            'community_benefit' => $project->community_benefit,

            'needs_funding' => $project->needs_funding,
            'budget' => $project->budget,
            'duration_months' => $project->duration_months,
            'support_type' => $project->support_type,
            'budget_breakdown' => $project->budget_breakdown,

            'milestones' => array_values(array_filter([
                $this->formatMilestone($project->milestone_1, $project->milestone_1_month),
                $this->formatMilestone($project->milestone_2, $project->milestone_2_month),
                $this->formatMilestone($project->milestone_3, $project->milestone_3_month),
            ])),

            'frontend_url' => $project->frontend_url,
            'backend_url' => $project->backend_url,
            'api_health_url' => $project->api_health_url,
            'admin_panel_url' => $project->admin_panel_url,
            'deployment_notes' => $project->deployment_notes,

            'scan_score' => $project->scan_score,
            'scanner_status' => $project->scanner_status,
            'final_decision' => $project->final_decision,
            'final_notes' => $project->final_notes,
            'final_decided_at' => optional($project->final_decided_at)?->format('Y-m-d H:i'),

            'approved_reviews_count' => $project->approvedReviews()->count(),
            'approved_investments_count' => $project->approvedInvestments()->count(),

            'files' => $project->files->map(function ($file) {
                return [
                    'type' => $file->file_type,
                    'path' => $file->file_path,
                ];
            })->values()->all(),
        ];
    }

    private function formatMilestone($title, $month): ?array
    {
        if (!$title) {
            return null;
        }

        return [
            'title' => $title,
            'month' => $month,
        ];
    }
}