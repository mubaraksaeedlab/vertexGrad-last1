<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Project;
use Illuminate\Http\Request;

class SupervisorDashboardController extends Controller
{
    public function index()
    {
        $user = auth('admin')->user();
        abort_unless($user && $user->role === 'Supervisor', 403);

        /*
        |--------------------------------------------------------------------------
        | Base query
        |--------------------------------------------------------------------------
        */
        $projectsQuery = Project::with(['student', 'supervisor']);

        /*
        |--------------------------------------------------------------------------
        | Stats
        |--------------------------------------------------------------------------
        */
        $totalProjects = (clone $projectsQuery)->count();

        $pendingReviews = (clone $projectsQuery)
            ->where(function ($query) {
                $query->whereIn('status', [
                    'pending',
                    'awaiting_manual_review',
                    'scan_requested'
                ])->orWhereNull('supervisor_status')
                  ->orWhereIn('supervisor_status', ['pending', 'under_review']);
            })
            ->count();

        $approvedProjects = (clone $projectsQuery)
            ->where(function ($query) {
                $query->where('supervisor_status', 'approved')
                      ->orWhere('supervisor_decision', 'approved')
                      ->orWhere('status', 'approved');
            })
            ->count();

        $revisionRequested = (clone $projectsQuery)
            ->where(function ($query) {
                $query->where('supervisor_status', 'revision_requested')
                      ->orWhere('supervisor_decision', 'revision_requested');
            })
            ->count();

        $stats = [
            'total_projects'     => $totalProjects,
            'pending_reviews'    => $pendingReviews,
            'approved_projects'  => $approvedProjects,
            'revision_requested' => $revisionRequested,
        ];

        /*
        |--------------------------------------------------------------------------
        | Latest projects
        |--------------------------------------------------------------------------
        */
        $latestProjects = (clone $projectsQuery)
            ->latest('updated_at')
            ->paginate(8);

        /*
        |--------------------------------------------------------------------------
        | Announcements (🔥 الجديد)
        |--------------------------------------------------------------------------
        */
        $announcements = Announcement::published()
            ->where(function ($query) {
                $query->where('audience', 'all')
                      ->orWhere('audience', 'supervisors');
            })
            ->ordered()
            ->get();

        return view('supervisor.dashboard', compact(
            'user',
            'stats',
            'latestProjects',
            'announcements'
        ));
    }
}