<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Barryvdh\DomPDF\Facade\Pdf;

class PlatformReportExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new InvestorsSheet(),
            new StudentsSheet(),
            new ProjectsSheet(),
        ];
    }
    public function exportPdf()
{
    $investors = \App\Models\User::where('role', 'Investor')->get();
    $students  = \App\Models\User::where('role', 'Student')->with('student')->get();
    $projects  = \App\Models\Project::with(['student','supervisor','investor'])->get();

    $pdf = Pdf::loadView('reports.platform.pdf', compact(
        'investors',
        'students',
        'projects'
    ))->setPaper('A4', 'portrait');

    return $pdf->download('platform_report.pdf');
}

}


