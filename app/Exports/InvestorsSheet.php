<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InvestorsSheet implements FromCollection, WithHeadings
{
    public function collection()
    {
        return User::where('role', 'Investor')->get([
            'id','name','email','status'
        ]);
    }

    public function headings(): array
    {
        return ['ID','Name','Email','Status'];
    }
}
