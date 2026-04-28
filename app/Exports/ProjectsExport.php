<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Project::with(['student','supervisor','manager','investor'])
                      ->get()
                      ->map(function($project) {
                          return [
                              'project_name' => $project->name,
                              'student' => $project->student->name ?? '—',
                              'supervisor' => $project->supervisor->name ?? '—',
                              'manager' => $project->manager->name ?? '—',
                              'investor' => $project->investor->name ?? '—',
                              'status' => $project->status,
                          ];
                      });
    }

    public function headings(): array
    {
        return ['Project Name', 'Student', 'Supervisor', 'Manager', 'Investor', 'Status'];
    }
}
