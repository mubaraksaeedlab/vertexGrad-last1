<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\InvestorActivity;
use App\Models\ProjectInvestment;
use App\Exports\InvestorReportsExport;
use Maatwebsite\Excel\Facades\Excel;

class InvestorReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_investors'   => Investor::count(),
            'active_investors'  => Investor::whereHas('user', fn($q) => $q->where('status', 'active'))->count(),
            'inactive_investors'=> Investor::whereHas('user', fn($q) => $q->where('status', 'inactive'))->count(),
            'archived_investors'=> Investor::onlyTrashed()->count(),

            'total_requests'    => ProjectInvestment::count(),
            'interested'        => ProjectInvestment::where('status', 'interested')->count(),
            'requested'         => ProjectInvestment::where('status', 'requested')->count(),
            'approved'          => ProjectInvestment::where('status', 'approved')->count(),
            'rejected'          => ProjectInvestment::where('status', 'rejected')->count(),

            'approved_amount'   => ProjectInvestment::where('status', 'approved')->sum('amount'),
        ];

        $topApprovedInvestors = ProjectInvestment::query()
            ->with('investor')
            ->where('status', 'approved')
            ->selectRaw('investor_id, COUNT(*) as approved_count, COALESCE(SUM(amount),0) as approved_amount')
            ->groupBy('investor_id')
            ->orderByDesc('approved_amount')
            ->take(5)
            ->get();

        $topRequestInvestors = ProjectInvestment::query()
            ->with('investor')
            ->selectRaw('investor_id, COUNT(*) as total_requests')
            ->groupBy('investor_id')
            ->orderByDesc('total_requests')
            ->take(5)
            ->get();

        $latestActivities = InvestorActivity::with(['investor.user', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.investor-reports.index', compact(
            'stats',
            'topApprovedInvestors',
            'topRequestInvestors',
            'latestActivities'
        ));
    }

    public function export()
    {
        $fileName = 'investor_reports_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new InvestorReportsExport, $fileName);
    }
}