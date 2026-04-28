<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuditLogsExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle, WithEvents
{
    public function __construct(
        protected Collection $logs,
        protected array $filters = [],
        protected Carbon $exportedAt = new Carbon()
    ) {}

    public function array(): array
    {
        $rows = [];

        $rows[] = ['VertexGrad Audit Logs Report'];
        $rows[] = ['Professional activity export'];
        $rows[] = ['Export Date: ' . $this->exportedAt->format('Y-m-d')];
        $rows[] = ['Export Time: ' . $this->exportedAt->format('h:i:s A')];
        $rows[] = ['Applied Filters: ' . $this->formatFilters()];
        $rows[] = [];

        $rows[] = [
            'ID',
            'User ID',
            'User Name',
            'User Type',
            'Event',
            'Category',
            'Subject Title',
            'Description',
            'Subject Type',
            'Subject ID',
            'IP Address',
            'Created Date',
            'Created Time',
            'Old Values',
            'New Values',
            'Properties',
        ];

        foreach ($this->logs as $log) {
            $rows[] = [
                $log->id,
                $log->user_id,
                $log->user_name,
                $log->user_type,
                $log->event,
                $log->category,
                $log->subject_title,
                $log->description,
                $log->subject_type,
                $log->subject_id,
                $log->ip_address,
                optional($log->created_at)->format('Y-m-d'),
                optional($log->created_at)->format('h:i:s A'),
                $this->jsonToText($log->old_values),
                $this->jsonToText($log->new_values),
                $this->jsonToText($log->properties),
            ];
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Audit Logs';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 18],
                'alignment' => ['horizontal' => 'center'],
            ],
            2 => [
                'font' => ['italic' => true, 'size' => 11],
                'alignment' => ['horizontal' => 'center'],
            ],
            3 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            5 => ['font' => ['bold' => true]],
            7 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '1D4ED8'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastColumn = 'P';
                $lastRow = 7 + $this->logs->count();

                $sheet->mergeCells("A1:{$lastColumn}1");
                $sheet->mergeCells("A2:{$lastColumn}2");
                $sheet->mergeCells("A3:{$lastColumn}3");
                $sheet->mergeCells("A4:{$lastColumn}4");
                $sheet->mergeCells("A5:{$lastColumn}5");

                $sheet->freezePane('A8');

                $sheet->getStyle("A7:{$lastColumn}{$lastRow}")
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                $sheet->getStyle("H8:P{$lastRow}")
                    ->getAlignment()
                    ->setWrapText(true);

                $sheet->getStyle("A7:{$lastColumn}{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $sheet->getRowDimension(1)->setRowHeight(28);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getDefaultRowDimension()->setRowHeight(22);
            },
        ];
    }

    protected function formatFilters(): string
    {
        $items = [];

        foreach ([
            'search' => 'Search',
            'user' => 'User',
            'event' => 'Event',
            'category' => 'Category',
            'from' => 'From',
            'to' => 'To',
        ] as $key => $label) {
            $value = $this->filters[$key] ?? null;
            if (!blank($value)) {
                $items[] = "{$label}: {$value}";
            }
        }

        return count($items) ? implode(' | ', $items) : 'No filters applied';
    }

    protected function jsonToText($value): ?string
    {
        if (blank($value)) {
            return null;
        }

        return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}