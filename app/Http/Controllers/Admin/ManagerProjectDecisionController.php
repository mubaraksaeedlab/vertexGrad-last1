<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Notifications\GeneralNotification;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class ManagerProjectDecisionController extends Controller
{
    protected function currentManager()
    {
        $user = auth('admin')->user();

        abort_unless($user && in_array($user->role, ['Manager', 'Admin']), 403);

        return $user;
    }

    public function index()
    {
        $manager = $this->currentManager();

        $projects = Project::with([
                'student',
                'reviews.supervisor',
                'finalDecisionMaker',
            ])
            ->whereHas('reviews')
            ->latest('updated_at')
            ->paginate(12);

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed final decisions projects list',
            category: 'project_final_decision',
            properties: [
                'manager_id' => $manager->id,
                'manager_name' => $manager->name ?? $manager->username,
                'projects_count_on_page' => $projects->count(),
                'current_page' => $projects->currentPage(),
            ]
        );

        return view('admin.projects.final-decisions.index', compact('projects'));
    }

    public function show(Project $project)
    {
        $manager = $this->currentManager();

        $project->load([
            'student',
            'supervisor',
            'manager',
            'investors',
            'media',
            'reviews.supervisor',
            'finalDecisionMaker',
        ]);

        $averageScore = round($project->reviews->whereNotNull('score')->avg('score') ?? 0, 1);
        $approvedCount = $project->reviews->where('decision', 'approved')->count();
        $revisionCount = $project->reviews->where('decision', 'revision_requested')->count();
        $rejectedCount = $project->reviews->where('decision', 'rejected')->count();

        $projectAddedNotification = $manager->unreadNotifications()
            ->where('type', GeneralNotification::class)
            ->get()
            ->first(function ($notification) use ($project) {
                return ($notification->data['project_id'] ?? null) == $project->project_id
                    && ($notification->data['category'] ?? null) === 'project_added';
            });

        if ($projectAddedNotification) {
            $projectAddedNotification->markAsRead();
        }

        AuditLogService::log(
            event: 'viewed',
            description: 'Viewed final decision details for project: ' . $project->name,
            category: 'project_final_decision',
            subject: $project,
            properties: [
                'manager_id' => $manager->id,
                'manager_name' => $manager->name ?? $manager->username,
                'average_score' => $averageScore,
                'approved_count' => $approvedCount,
                'revision_count' => $revisionCount,
                'rejected_count' => $rejectedCount,
                'project_added_notification_marked' => (bool) $projectAddedNotification,
            ]
        );

        return view('admin.projects.final-decisions.show', compact(
            'project',
            'averageScore',
            'approvedCount',
            'revisionCount',
            'rejectedCount',
            'projectAddedNotification'
        ));
    }

    public function storeDecision(Request $request, Project $project)
    {
        $manager = $this->currentManager();

        $validated = $request->validate([
            'final_decision' => ['required', 'in:published,revision_requested,rejected'],
            'final_notes' => ['nullable', 'string'],
        ]);

        $project->load([
            'student',
            'reviews.supervisor',
            'finalDecisionMaker',
        ]);

        $averageScore = round($project->reviews->whereNotNull('score')->avg('score') ?? 0, 1);
        $approvedCount = $project->reviews->where('decision', 'approved')->count();
        $revisionCount = $project->reviews->where('decision', 'revision_requested')->count();
        $rejectedCount = $project->reviews->where('decision', 'rejected')->count();

        $oldValues = $this->auditDecisionPayload($project);

        $project->update([
            'final_decision'   => $validated['final_decision'],
            'final_notes'      => $validated['final_notes'] ?? null,
            'final_decided_at' => now(),
            'final_decided_by' => $manager->id,
            'manager_id'       => $manager->id,
            'status'           => $validated['final_decision'],
        ]);

        $project->refresh();
        $project->load([
            'student',
            'reviews.supervisor',
            'finalDecisionMaker',
        ]);

        AuditLogService::log(
            event: 'final_decision_saved',
            description: 'Saved final decision "' . $validated['final_decision'] . '" for project: ' . $project->name,
            category: 'project_final_decision',
            subject: $project,
            oldValues: $oldValues,
            newValues: $this->auditDecisionPayload($project),
            properties: [
                'manager_id' => $manager->id,
                'manager_name' => $manager->name ?? $manager->username,
                'average_score' => $averageScore,
                'approved_count' => $approvedCount,
                'revision_count' => $revisionCount,
                'rejected_count' => $rejectedCount,
            ]
        );

        if ($project->student) {
            $title = match ($validated['final_decision']) {
                'published' => 'Project Approved for Publishing',
                'revision_requested' => 'Project Requires Revisions',
                'rejected' => 'Project Rejected',
                default => 'Project Final Decision Updated',
            };

            $message = match ($validated['final_decision']) {
                'published' => 'Your project has been finally approved and published by management.',
                'revision_requested' => 'Management requested revisions after reviewing supervisor evaluations.',
                'rejected' => 'Your project was rejected after the final management review.',
                default => 'The final decision for your project has been updated.',
            };

            $project->student->notify(new GeneralNotification([
                'title'      => $title,
                'message'    => $message,
                'url'        => route('dashboard.academic', ['project' => $project->project_id]),
                'icon'       => 'fas fa-gavel',
                'project_id' => $project->project_id,
            ]));
        }

        return redirect()
            ->route('admin.projects.final-decisions.show', $project->project_id)
            ->with('success', 'Final decision saved successfully.');
    }

    protected function auditDecisionPayload(Project $project): array
    {
        return [
            'project_id'        => $project->project_id,
            'name'              => $project->name,
            'status'            => $project->status,
            'final_decision'    => $project->final_decision,
            'final_notes'       => $project->final_notes,
            'final_decided_at'  => $project->final_decided_at,
            'final_decided_by'  => $project->final_decided_by,
            'manager_id'        => $project->manager_id,
            'student_id'        => $project->student_id,
            'supervisor_id'     => $project->supervisor_id,
            'scanner_status'    => $project->scanner_status,
            'scanner_project_id'=> $project->scanner_project_id,
        ];
    }
}