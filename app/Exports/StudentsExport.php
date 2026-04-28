<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role', 'Student')
                   ->with('student')
                   ->get()
                   ->map(function($user) {
                       return [
                           'name' => $user->name,
                           'email' => $user->email,
                           'major' => $user->student->major ?? '—',
                           'status' => $user->status
                       ];
                   });
    }

    public function headings(): array
    {
        return ['Name', 'Email', 'Major', 'Status'];
    }
}
