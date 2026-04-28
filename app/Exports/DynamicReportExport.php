<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DynamicReportExport implements FromArray, WithHeadings
{
    protected array $report;

    public function __construct(array $report)
    {
        $this->report = $report;
    }

    public function headings(): array
    {
        return $this->report['headings'] ?? [];
    }

    public function array(): array
    {
        return collect($this->report['rows'] ?? [])
            ->map(fn ($row) => array_values($row))
            ->toArray();
    }
}