<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'        => User::count(),
            'active_users'       => User::where('status', 'active')->count(),
            'students'           => User::where('role', 'Student')->count(),
            'investors'          => User::where('role', 'Investor')->count(),
            'managers'           => User::where('role', 'Manager')->count(),

            'total_projects'     => Project::count(),
            'pending_projects'   => Project::where('status', 'pending')->count(),
            'active_projects'    => Project::whereIn('status', ['active', 'published'])->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'rejected_projects'  => Project::where('status', 'rejected')->count(),

            'total_funding'      => Project::whereIn('status', ['active', 'published', 'completed'])->sum('budget'),
            'reports'            => Schema::hasTable('report_exports') ? \DB::table('report_exports')->count() : 0,
        ];

        /*
        |--------------------------------------------------------------------------
        | Daily Chart - Current Week Real Data
        |--------------------------------------------------------------------------
        */
        $startOfWeek = now()->startOfWeek();

        $days = collect(range(0, 6))->map(function ($i) use ($startOfWeek) {
            return $startOfWeek->copy()->addDays($i);
        });

        $chartDaily = [
            'labels' => $days->map(fn ($day) => $day->format('D'))->toArray(),

            'submitted' => $days->map(fn ($day) =>
                Project::whereDate('created_at', $day)->count()
            )->toArray(),

            'approved' => $days->map(fn ($day) =>
                Project::whereDate('updated_at', $day)
                    ->whereIn('status', ['active', 'published', 'completed'])
                    ->count()
            )->toArray(),

            'rejected' => $days->map(fn ($day) =>
                Project::whereDate('updated_at', $day)
                    ->where('status', 'rejected')
                    ->count()
            )->toArray(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Weekly Chart - Last 4 Weeks Real Data
        |--------------------------------------------------------------------------
        */
        $weeks = collect(range(3, 0))->map(function ($i) {
            return now()->subWeeks($i);
        });

        $chartWeekly = [
            'labels' => $weeks->map(fn ($week) => 'W' . $week->weekOfYear)->toArray(),

            'submitted' => $weeks->map(fn ($week) =>
                Project::whereBetween('created_at', [
                    $week->copy()->startOfWeek(),
                    $week->copy()->endOfWeek(),
                ])->count()
            )->toArray(),

            'approved' => $weeks->map(fn ($week) =>
                Project::whereIn('status', ['active', 'published', 'completed'])
                    ->whereBetween('updated_at', [
                        $week->copy()->startOfWeek(),
                        $week->copy()->endOfWeek(),
                    ])
                    ->count()
            )->toArray(),

            'rejected' => $weeks->map(fn ($week) =>
                Project::where('status', 'rejected')
                    ->whereBetween('updated_at', [
                        $week->copy()->startOfWeek(),
                        $week->copy()->endOfWeek(),
                    ])
                    ->count()
            )->toArray(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Monthly Chart - Last 6 Months Real Data
        |--------------------------------------------------------------------------
        */
        $monthsCollection = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i)->startOfMonth();
        });

        $chartMonthly = [
            'labels' => $monthsCollection->map(fn ($month) => $month->format('M'))->toArray(),

            'submitted' => $monthsCollection->map(fn ($month) =>
                Project::whereBetween('created_at', [
                    $month->copy()->startOfMonth(),
                    $month->copy()->endOfMonth(),
                ])->count()
            )->toArray(),

            'approved' => $monthsCollection->map(fn ($month) =>
                Project::whereIn('status', ['active', 'published', 'completed'])
                    ->whereBetween('updated_at', [
                        $month->copy()->startOfMonth(),
                        $month->copy()->endOfMonth(),
                    ])
                    ->count()
            )->toArray(),

            'rejected' => $monthsCollection->map(fn ($month) =>
                Project::where('status', 'rejected')
                    ->whereBetween('updated_at', [
                        $month->copy()->startOfMonth(),
                        $month->copy()->endOfMonth(),
                    ])
                    ->count()
            )->toArray(),
        ];

        $recentProjects = Project::with(['student', 'manager'])
            ->latest('project_id')
            ->take(6)
            ->get();

        $recentStudents = User::where('role', 'Student')
            ->latest('id')
            ->take(5)
            ->get();

        $recentInvestors = User::where('role', 'Investor')
            ->latest('id')
            ->take(5)
            ->get();

        return view('manager.dashboard', compact(
            'stats',
            'chartDaily',
            'chartWeekly',
            'chartMonthly',
            'recentProjects',
            'recentStudents',
            'recentInvestors'
        ));
    }

    public function dashboard()
    {
        return $this->index();
    }
}