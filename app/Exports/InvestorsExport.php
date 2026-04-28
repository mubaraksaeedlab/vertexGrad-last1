<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;

class InvestorsExport implements FromCollection, WithMapping, WithHeadings, WithEvents, WithCustomStartCell
{
    public function collection()
    {
        return User::where('role', 'Investor')->get();
    }

    public function map($investor): array
    {
        return [
            $investor->id,
            $investor->name,
            $investor->email,
            $investor->status,
        ];
    }

    // تحديد الصف الذي تبدأ منه رؤوس الجدول
    public function startCell(): string
    {
        return 'A4';
    }

    public function headings(): array
    {
        return ['#', 'Name', 'Email', 'Status'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // عنوان التقرير في الصف 1
                $sheet->mergeCells('A1:D1');
                $sheet->setCellValue('A1', '📊 Investors Report');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // تاريخ التصدير في الصف 2
                $sheet->mergeCells('A2:D2');
                $sheet->setCellValue('A2', 'Exported on: ' . now()->format('Y-m-d H:i'));
                $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(12);
                $sheet->getStyle('A2')->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // تنسيق رؤوس الجدول (الصف 4)
                $sheet->getStyle('A4:D4')->getFont()->setBold(true);
                $sheet->getStyle('A4:D4')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF1B00FF'); // أزرق
                $sheet->getStyle('A4:D4')->getFont()->getColor()->setARGB('FFFFFFFF'); // أبيض

                // ضبط عرض الأعمدة تلقائيًا
                foreach(range('A','D') as $col){
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
