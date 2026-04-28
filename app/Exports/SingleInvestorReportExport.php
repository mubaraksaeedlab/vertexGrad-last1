<?php

namespace App\Exports;

use App\Models\Investor;
use App\Models\ProjectInvestment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SingleInvestorReportExport implements FromCollection, WithHeadings
{
    protected $investor;

    public function __construct(Investor $investor)
    {
        $this->investor = $investor;
    }

    public function collection()
    {
        return ProjectInvestment::with(['project', 'investor'])
            ->where('investor_id', $this->investor->user_id)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'investor_name'  => optional($item->investor)->name,
                    'investor_email' => optional($item->investor)->email,
                    'project_name'   => optional($item->project)->name,
                    'status'         => $item->status,
                    'amount'         => $item->amount,
                    'message'        => $item->message,
                    'created_at'     => optional($item->created_at)->format('Y-m-d H:i:s'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Investor Name',
            'Investor Email',
            'Project',
            'Status',
            'Amount',
            'Message',
            'Created At',
        ];
    }
}