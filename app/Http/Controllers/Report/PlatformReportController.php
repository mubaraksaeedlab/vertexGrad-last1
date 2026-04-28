<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PlatformReportExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvestorsExport;
use App\Exports\StudentsExport;
use App\Exports\ProjectsExport;

class PlatformReportController extends Controller
{
    // Export Investors
    public function exportInvestorsExcel() {
        return Excel::download(new InvestorsExport, 'investors.xlsx');
    }
    public function exportInvestorsPdf() {
        $investors = User::where('role','Investor')->get();
        $pdf = Pdf::loadView('reports.platform.pdf_investors', compact('investors'));
        return $pdf->download('investors.pdf');
    }

    // Export Students
    public function exportStudentsExcel() {
        return Excel::download(new StudentsExport, 'students.xlsx');
    }
    public function exportStudentsPdf() {
        $students = User::where('role','Student')->with('student')->get();
        $pdf = Pdf::loadView('reports.platform.pdf_students', compact('students'));
        return $pdf->download('students.pdf');
    }

    // Export Projects
    public function exportProjectsExcel() {
        return Excel::download(new ProjectsExport, 'projects.xlsx');
    }
    public function exportProjectsPdf() {
        $projects = Project::with(['student','supervisor','manager','investor'])->get();
        $pdf = Pdf::loadView('reports.platform.pdf_projects', compact('projects'));
        return $pdf->download('projects.pdf');
    }


    // ✅ هذه الدالة يجب أن تكون موجودة
    public function exportPdf()
    {
        $investors = User::where('role', 'Investor')->get();
        $students  = User::where('role', 'Student')->with('student')->get();
        $projects  = Project::with(['student','supervisor','investor'])->get();

        $pdf = Pdf::loadView(
            'reports.platform.pdf',
            compact('investors', 'students', 'projects')
        )->setPaper('A4', 'portrait');

        return $pdf->download('platform_report.pdf');
    }
  

public function exportExcel()
{
    return Excel::download(new PlatformReportExport, 'platform_report.xlsx');
}
public function index()
{
    $investors = User::where('role','Investor')->get();
    $students  = User::where('role','Student')->with('student')->get();
    $projects  = Project::with(['student','supervisor','manager','investor'])->get();

    return view('reports.platform.index', compact('investors','students','projects'));
}


}

