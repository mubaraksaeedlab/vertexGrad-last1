<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;

class HomeController extends Controller
{
    public function index()
    {
        $homeProjects = Project::with(['media', 'student', 'projectCategory'])
            ->whereIn('status', ['active', 'published'])
            ->latest('project_id')
            ->take(4)
            ->get();

        $stats = [
            'active_projects' => Project::whereIn('status', ['active', 'published'])->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'total_visible_projects' => Project::whereIn('status', ['active', 'published', 'completed'])->count(),
            'total_funding' => Project::whereIn('status', ['active', 'published', 'completed'])->sum('budget'),
        ];

        return view('frontend.pages.home', compact(
            'homeProjects',
            'stats'
        ));
    }
}