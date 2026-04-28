<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectApprovedNotification;
use App\Notifications\ProjectRejectedNotification;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with([
            'student',
            'supervisor',
            'manager',
            'media',
            'investors',
            'reviews',
            'finalDecisionMaker',
        ]);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('budget_max')) {
            $query->where('budget', '<=', $request->budget_max);
        }

        $projects = $query->latest('project_id')->paginate(10)->appends($request->all());

        $totalProjects = Project::count();

        $completedProjects = Project::where('status', 'completed')->count();

        $pendingProjects = Project::whereIn('status', [
            'pending',
            'scan_requested',
            'awaiting_manual_review',
        ])->count();

        $avgScore = Project::whereNotNull('scan_score')->avg('scan_score');

        return view('projects.index', compact(
            'projects',
            'totalProjects',
            'completedProjects',
            'pendingProjects',
            'avgScore'
        ));
    }

    public function create()
    {
        $students = User::where('role', 'Student')->get();
        $supervisors = User::where('role', 'Supervisor')->get();
        $managers = User::where('role', 'Manager')->get();
        $investors = User::where('role', 'Investor')->get();

        return view('projects.create', compact('students', 'supervisors', 'managers', 'investors'));
    }

    public function publish(Project $project)
    {
        $user = auth('admin')->user();

        if (! in_array($project->status, ['approved', 'active', 'awaiting_manual_review'])) {
            return back()->with('error', 'Only approved or active projects can be published.');
        }

        $oldValues = $this->auditPayload($project);

        $project->update([
            'status' => 'published',
            'manager_id' => $project->manager_id ?: $user?->id,
        ]);

        $project->refresh();

        AuditLogService::log(
            event: 'published',
            description: 'Published project: ' . $project->name,
            category: 'project',
            subject: $project,
            oldValues: $oldValues,
            newValues: $this->auditPayload($project),
            properties: [
                'published_by' => $user?->id,
                'published_by_name' => $user?->name ?? $user?->username,
            ]
        );

        return back()->with('success', 'Project published successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:150',
            'description'      => 'nullable|string',
            'category'         => 'nullable|string|max:50',
            'student_id'       => 'required|exists:users,id',
            'supervisor_id'    => 'nullable|exists:users,id',
            'manager_id'       => 'nullable|exists:users,id',
            'investor_id'      => 'nullable|exists:users,id',
            'budget'           => 'nullable|numeric',
            'priority'         => 'nullable|in:Low,Medium,High',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date|after_or_equal:start_date',
            'progress'         => 'nullable|integer|min:0|max:100',
            'is_featured'      => 'nullable|boolean',
            'project_photos.*' => 'nullable|image|max:5120',
            'project_video'    => 'nullable|mimes:mp4,mov,ogg,qt|max:51200',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        $project = Project::create($data);

        if ($request->hasFile('project_photos')) {
            foreach ($request->file('project_photos') as $img) {
                $project->addMedia($img)->toMediaCollection('images');
            }
        }

        if ($request->hasFile('project_video')) {
            $project->addMedia($request->file('project_video'))->toMediaCollection('videos');
        }

        AuditLogService::log(
            event: 'created',
            description: 'Created project: ' . $project->name,
            category: 'project',
            subject: $project,
            newValues: $this->auditPayload($project),
            properties: [
                'student_id'    => $project->student_id,
                'supervisor_id' => $project->supervisor_id,
                'manager_id'    => $project->manager_id,
                'investor_id'   => $project->investor_id,
                'has_images'    => $request->hasFile('project_photos'),
                'has_video'     => $request->hasFile('project_video'),
            ]
        );

        return redirect()->route('admin.projects.index')
            ->with('success', 'Project created successfully!');
    }

    public function show(Project $project)
    {
        $project->load([
            'student',
            'supervisor',
            'manager',
            'media',
            'files',
            'investors',
            'reviews',
            'finalDecisionMaker',
        ]);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $students = User::where('role', 'Student')->get();
        $supervisors = User::where('role', 'Supervisor')->get();
        $managers = User::where('role', 'Manager')->get();
        $investors = User::where('role', 'Investor')->get();

        return view('projects.edit', compact('project', 'students', 'supervisors', 'managers', 'investors'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:150',
            'description'   => 'nullable|string',
            'category'      => 'nullable|string|max:50',
            'status'        => 'required|in:pending,scan_requested,awaiting_manual_review,approved,published,active,completed,rejected,scan_failed',
            'budget'        => 'nullable|numeric',
            'priority'      => 'nullable|in:Low,Medium,High',
            'student_id'    => 'nullable|exists:users,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'manager_id'    => 'nullable|exists:users,id',
            'investor_id'   => 'nullable|exists:users,id',
        ]);

        $oldValues = $this->auditPayload($project);

        $project->update($data);
        $project->refresh();

        AuditLogService::log(
            event: 'updated',
            description: 'Updated project: ' . $project->name,
            category: 'project',
            subject: $project,
            oldValues: $oldValues,
            newValues: $this->auditPayload($project)
        );

        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        AuditLogService::log(
            event: 'deleted',
            description: 'Deleted project: ' . $project->name,
            category: 'project',
            subject: $project,
            oldValues: $this->auditPayload($project)
        );

        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }

    public function approve(Project $project)
    {
        $user = auth('admin')->user();

        if ($project->status !== 'pending') {
            return back()->with('error', 'Only pending projects can be approved.');
        }

        $oldValues = $this->auditPayload($project);

        $project->update([
            'status' => 'active',
            'manager_id' => $user->id,
        ]);

        $project->refresh();

        AuditLogService::log(
            event: 'approved',
            description: 'Approved project: ' . $project->name,
            category: 'project',
            subject: $project,
            oldValues: $oldValues,
            newValues: $this->auditPayload($project),
            properties: [
                'approved_by' => $user->id,
                'approved_by_name' => $user->name ?? $user->username,
            ]
        );

        if ($project->student) {
            $project->student->notify(new ProjectApprovedNotification($project));
        }

        $user->unreadNotifications()
            ->where('type', \App\Notifications\ProjectSubmittedNotification::class)
            ->get()
            ->filter(function ($notification) use ($project) {
                return ($notification->data['project_id'] ?? null) == $project->project_id;
            })
            ->each(function ($notification) {
                $notification->markAsRead();
            });

        return back()->with('success', 'Project approved and assigned to you.');
    }

    public function reject(Project $project)
    {
        $user = auth('admin')->user();

        if ($project->status !== 'pending') {
            return back()->with('error', 'Only pending projects can be rejected.');
        }

        $oldValues = $this->auditPayload($project);

        $project->update([
            'status' => 'rejected',
            'manager_id' => $user->id,
        ]);

        $project->refresh();

        AuditLogService::log(
            event: 'rejected',
            description: 'Rejected project: ' . $project->name,
            category: 'project',
            subject: $project,
            oldValues: $oldValues,
            newValues: $this->auditPayload($project),
            properties: [
                'rejected_by' => $user->id,
                'rejected_by_name' => $user->name ?? $user->username,
            ]
        );

        if ($project->student) {
            $project->student->notify(new ProjectRejectedNotification($project));
        }

        $user->unreadNotifications()
            ->where('type', \App\Notifications\ProjectSubmittedNotification::class)
            ->get()
            ->filter(function ($notification) use ($project) {
                return ($notification->data['project_id'] ?? null) == $project->project_id;
            })
            ->each(function ($notification) {
                $notification->markAsRead();
            });

        return back()->with('success', 'Project rejected successfully.');
    }

    public function approveInvestor(Project $project, User $user)
    {
        if ($user->role !== 'Investor') {
            return back()->with('error', 'Selected user is not an investor.');
        }

        $relation = $project->investors()
            ->where('users.id', $user->id)
            ->first();

        if (! $relation || $relation->pivot->status !== 'requested') {
            return back()->with('error', 'This investor does not have a pending funding request.');
        }

        $project->investors()->updateExistingPivot($user->id, [
            'status' => 'approved',
            'updated_at' => now(),
        ]);

        AuditLogService::log(
            event: 'approved',
            description: 'Approved investor funding request for project: ' . $project->name,
            category: 'project_investor',
            subject: $project,
            properties: [
                'investor_id' => $user->id,
                'investor_name' => $user->name,
                'pivot_status' => 'approved',
            ]
        );

        $user->notify(new \App\Notifications\FundingRequestApprovedNotification($project));

        return back()->with('success', 'Funding request approved successfully.');
    }

    public function rejectInvestor(Project $project, User $user)
    {
        if ($user->role !== 'Investor') {
            return back()->with('error', 'Selected user is not an investor.');
        }

        $relation = $project->investors()
            ->where('users.id', $user->id)
            ->first();

        if (! $relation || $relation->pivot->status !== 'requested') {
            return back()->with('error', 'This investor does not have a pending funding request.');
        }

        $project->investors()->updateExistingPivot($user->id, [
            'status' => 'rejected',
            'updated_at' => now(),
        ]);

        AuditLogService::log(
            event: 'rejected',
            description: 'Rejected investor funding request for project: ' . $project->name,
            category: 'project_investor',
            subject: $project,
            properties: [
                'investor_id' => $user->id,
                'investor_name' => $user->name,
                'pivot_status' => 'rejected',
            ]
        );

        $user->notify(new \App\Notifications\FundingRequestRejectedNotification($project));

        return back()->with('success', 'Funding request rejected successfully.');
    }

    public function sendToScanner(Project $project)
    {
        if (! $project->student) {
            return redirect()
                ->route('admin.projects.edit', $project)
                ->with('error', 'This project must be assigned to a student before sending it to scanner.');
        }

        try {
            $response = Http::withHeaders([
                'X-INTEGRATION-SECRET' => env('SCANNER_INTEGRATION_SECRET'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(env('SCANNER_INTEGRATION_URL'), [
                'name' => $project->name,
                'platform_project_id' => $project->project_id,
                'student_id' => $project->student_id,
                'student_name' => $project->student->name ?? 'Unknown Student',
                'student_email' => $project->student->email ?? null,
                'callback_url' => env('SCANNER_CALLBACK_URL'),
            ]);

            if (! $response->successful()) {
                AuditLogService::log(
                    event: 'scan_failed',
                    description: 'Failed to send project to scanner: ' . $project->name,
                    category: 'project_scanner',
                    subject: $project,
                    properties: [
                        'http_status' => $response->status(),
                    ]
                );

                return redirect()
                    ->route('admin.projects.edit', $project)
                    ->with('error', 'Failed to connect to scanner platform.');
            }

            $payload = $response->json();
            $scannerData = $payload['data'] ?? [];

            if (! ($payload['success'] ?? false)) {
                AuditLogService::log(
                    event: 'scan_failed',
                    description: 'Scanner integration failed for project: ' . $project->name,
                    category: 'project_scanner',
                    subject: $project,
                    properties: [
                        'payload_message' => $payload['message'] ?? null,
                    ]
                );

                return redirect()
                    ->route('admin.projects.edit', $project)
                    ->with('error', $payload['message'] ?? 'Scanner integration failed.');
            }

            if (empty($scannerData['scanner_project_id']) || empty($scannerData['token'])) {
                AuditLogService::log(
                    event: 'scan_failed',
                    description: 'Scanner response missing data for project: ' . $project->name,
                    category: 'project_scanner',
                    subject: $project
                );

                return redirect()
                    ->route('admin.projects.edit', $project)
                    ->with('error', 'Scanner response is missing required data.');
            }

            $oldValues = $this->auditPayload($project);

            $project->update([
                'status' => 'scan_requested',
                'scanner_status' => 'pending',
                'scanner_project_id' => $scannerData['scanner_project_id'],
            ]);

            $project->refresh();

            AuditLogService::log(
                event: 'scan_requested',
                description: 'Sent project to scanner: ' . $project->name,
                category: 'project_scanner',
                subject: $project,
                oldValues: $oldValues,
                newValues: $this->auditPayload($project),
                properties: [
                    'scanner_project_id' => $scannerData['scanner_project_id'],
                ]
            );

            $scanUrl = rtrim(env('SCANNER_PUBLIC_BASE_URL'), '/') . '/?project_token=' . urlencode($scannerData['token']);

            return redirect()->away($scanUrl);
        } catch (\Throwable $e) {
            Log::error('Admin sendToScanner failed', [
                'project_id' => $project->project_id,
                'message' => $e->getMessage(),
            ]);

            AuditLogService::log(
                event: 'scan_failed',
                description: 'Unexpected scanner error for project: ' . $project->name,
                category: 'project_scanner',
                subject: $project,
                properties: [
                    'error_message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->route('admin.projects.edit', $project)
                ->with('error', 'An unexpected error occurred while sending the project to scanner.');
        }
    }

    public function scannerReview(Project $project)
    {
        $project->load('student');

        if (! $project->student) {
            return redirect()
                ->route('admin.projects.edit', $project)
                ->with('error', 'This project must be assigned to a student before starting scanner review.');
        }

        return view('projects.scanner-review', compact('project'));
    }

    public function startScan(Project $project)
    {
        $project->load('student');

        if (! $project->student) {
            return redirect()
                ->route('admin.projects.edit', $project)
                ->with('error', 'This project must be assigned to a student before sending it to scanner.');
        }

        try {
            $response = Http::withHeaders([
                'X-INTEGRATION-SECRET' => env('SCANNER_INTEGRATION_SECRET'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post(env('SCANNER_INTEGRATION_URL'), [
                'name' => $project->name,
                'platform_project_id' => $project->project_id,
                'student_id' => $project->student_id,
                'student_name' => $project->student->name ?? 'Unknown Student',
                'student_email' => $project->student->email ?? null,
                'callback_url' => env('SCANNER_CALLBACK_URL'),
            ]);

            if (! $response->successful()) {
                AuditLogService::log(
                    event: 'scan_failed',
                    description: 'Failed to start scanner review for project: ' . $project->name,
                    category: 'project_scanner',
                    subject: $project,
                    properties: [
                        'http_status' => $response->status(),
                    ]
                );

                return redirect()
                    ->route('admin.projects.scannerReview', $project)
                    ->with('error', 'Failed to connect to scanner platform.');
            }

            $payload = $response->json();
            $scannerData = $payload['data'] ?? [];

            if (! ($payload['success'] ?? false)) {
                AuditLogService::log(
                    event: 'scan_failed',
                    description: 'Scanner review integration failed for project: ' . $project->name,
                    category: 'project_scanner',
                    subject: $project,
                    properties: [
                        'payload_message' => $payload['message'] ?? null,
                    ]
                );

                return redirect()
                    ->route('admin.projects.scannerReview', $project)
                    ->with('error', $payload['message'] ?? 'Scanner integration failed.');
            }

            if (empty($scannerData['scanner_project_id']) || empty($scannerData['token'])) {
                AuditLogService::log(
                    event: 'scan_failed',
                    description: 'Scanner review response missing data for project: ' . $project->name,
                    category: 'project_scanner',
                    subject: $project
                );

                return redirect()
                    ->route('admin.projects.scannerReview', $project)
                    ->with('error', 'Scanner response is missing required data.');
            }

            $oldValues = $this->auditPayload($project);

            $project->update([
                'status' => 'scan_requested',
                'scanner_status' => 'pending',
                'scanner_project_id' => $scannerData['scanner_project_id'],
            ]);

            $project->refresh();

            AuditLogService::log(
                event: 'scan_requested',
                description: 'Started scanner review for project: ' . $project->name,
                category: 'project_scanner',
                subject: $project,
                oldValues: $oldValues,
                newValues: $this->auditPayload($project),
                properties: [
                    'scanner_project_id' => $scannerData['scanner_project_id'],
                ]
            );

            $scanUrl = rtrim(env('SCANNER_PUBLIC_BASE_URL'), '/') . '/?project_token=' . urlencode($scannerData['token']);

            return redirect()->away($scanUrl);
        } catch (\Throwable $e) {
            Log::error('Admin startScan failed', [
                'project_id' => $project->project_id,
                'message' => $e->getMessage(),
            ]);

            AuditLogService::log(
                event: 'scan_failed',
                description: 'Unexpected scanner review error for project: ' . $project->name,
                category: 'project_scanner',
                subject: $project,
                properties: [
                    'error_message' => $e->getMessage(),
                ]
            );

            return redirect()
                ->route('admin.projects.scannerReview', $project)
                ->with('error', 'An unexpected error occurred while sending the project to scanner.');
        }
    }

    protected function auditPayload(Project $project): array
    {
        return [
            'project_id'         => $project->project_id,
            'name'               => $project->name,
            'description'        => $project->description,
            'category'           => $project->category,
            'status'             => $project->status,
            'student_id'         => $project->student_id,
            'supervisor_id'      => $project->supervisor_id,
            'manager_id'         => $project->manager_id,
            'investor_id'        => $project->investor_id,
            'budget'             => $project->budget,
            'priority'           => $project->priority,
            'start_date'         => $project->start_date,
            'end_date'           => $project->end_date,
            'progress'           => $project->progress,
            'is_featured'        => $project->is_featured,
            'scanner_status'     => $project->scanner_status,
            'scanner_project_id' => $project->scanner_project_id,
        ];
    }
}