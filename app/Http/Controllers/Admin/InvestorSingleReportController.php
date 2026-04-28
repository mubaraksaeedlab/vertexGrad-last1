<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investor;
use App\Models\ProjectInvestment;
use App\Exports\SingleInvestorReportExport;
use Maatwebsite\Excel\Facades\Excel;

class InvestorSingleReportController extends Controller
{
    public function show(Investor $investor)
    {
        $investor->load([
            'user',
            'investorNotes.user',
            'files',
            'activities.user',
        ]);

        $investmentRequests = ProjectInvestment::with(['project', 'investor'])
            ->where('investor_id', $investor->user_id)
            ->latest()
            ->get();

        $stats = [
            'total_requests' => $investmentRequests->count(),
            'interested'     => $investmentRequests->where('status', 'interested')->count(),
            'requested'      => $investmentRequests->where('status', 'requested')->count(),
            'approved'       => $investmentRequests->where('status', 'approved')->count(),
            'rejected'       => $investmentRequests->where('status', 'rejected')->count(),
            'approved_amount'=> $investmentRequests->where('status', 'approved')->sum('amount'),
            'notes_count'    => $investor->investorNotes->count(),
            'files_count'    => $investor->files->count(),
            'activities_count'=> $investor->activities->count(),
        ];

        return view('investors.report', compact(
            'investor',
            'investmentRequests',
            'stats'
        ));
    }

    public function export(Investor $investor)
    {
        $fileName = 'investor_report_' . $investor->id . '_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new SingleInvestorReportExport($investor), $fileName);
    }
}