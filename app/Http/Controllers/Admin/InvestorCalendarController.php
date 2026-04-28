<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestorMeeting;
use App\Models\InvestorReminder;
use App\Models\InvestorContract;
use App\Models\InvestorActivity;
use Carbon\Carbon;

class InvestorCalendarController extends Controller
{
    public function index()
    {
        $today = Carbon::now();
        $next30Days = Carbon::now()->addDays(30);

        $upcomingMeetings = InvestorMeeting::with(['investor.user', 'creator'])
            ->whereBetween('meeting_at', [$today, $next30Days])
            ->orderBy('meeting_at')
            ->get();

        $upcomingReminders = InvestorReminder::with(['investor.user', 'creator'])
            ->whereBetween('remind_at', [$today, $next30Days])
            ->whereIn('status', ['pending', 'sent'])
            ->orderBy('remind_at')
            ->get();

        $expiringContracts = InvestorContract::with(['investor.user', 'creator'])
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [$today->copy()->startOfDay(), $next30Days->copy()->endOfDay()])
            ->orderBy('end_date')
            ->get();

        $latestActivities = InvestorActivity::with(['investor.user', 'user'])
            ->latest()
            ->take(15)
            ->get();

        $stats = [
            'upcoming_meetings'  => $upcomingMeetings->count(),
            'upcoming_reminders' => $upcomingReminders->count(),
            'expiring_contracts' => $expiringContracts->count(),
            'recent_activities'  => $latestActivities->count(),
        ];

        return view('admin.investor-calendar.index', compact(
            'upcomingMeetings',
            'upcomingReminders',
            'expiringContracts',
            'latestActivities',
            'stats'
        ));
    }
}