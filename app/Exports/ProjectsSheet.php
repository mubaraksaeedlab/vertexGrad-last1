<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectsSheet implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Project::with('student')->get()->map(function($pro){
            return [
                'id' => $pro->id,
                'name' => $pro->name,
                'student' => $pro->student->name ?? '-',
                'status' => $pro->status,
                'budget' => $pro->budget ?? 0
            ];
        });
    }

    public function headings(): array
    {
        return ['ID','Project Name','Student','Status','Budget'];
    }
}
