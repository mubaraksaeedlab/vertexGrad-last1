<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsSheet implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role', 'Student')
            ->with('student')
            ->get()
            ->map(function($stu){
                return [
                    'id' => $stu->id,
                    'name' => $stu->name,
                    'email' => $stu->email,
                    'major' => $stu->student->major ?? '-',
                    'status' => $stu->status
                ];
            });
    }

    public function headings(): array
    {
        return ['ID','Name','Email','Major','Status'];
    }
}
