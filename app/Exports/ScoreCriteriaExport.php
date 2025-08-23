<?php

namespace App\Exports;

use App\Models\ScoreCriteria;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ScoreCriteriaExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $format;

    public function __construct($format = 'xlsx')
    {
        $this->format = $format;
    }

    public function collection()
    {
        return ScoreCriteria::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name->en')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Criteria Name (English)',
            'Criteria Name (Arabic)',
            'Description (English)',
            'Description (Arabic)',
            'Points',
            'Type',
            'Achieved (✓)',
            'Notes'
        ];
    }

    public function map($criteria): array
    {
        return [
            $criteria->getTranslation('name', 'en'),
            $criteria->getTranslation('name', 'ar'),
            $criteria->getTranslation('description', 'en'),
            $criteria->getTranslation('description', 'ar'),
            $criteria->points > 0 ? '+' . $criteria->points : $criteria->points,
            $criteria->is_negative ? 'Penalty' : 'Achievement',
            '', // Empty checkbox column
            '', // Notes column
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Style the data rows
        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 1) {
            $sheet->getStyle('A2:H' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                ],
            ]);

            // Style the checkbox column
            $sheet->getStyle('G2:G' . $lastRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 16,
                ],
            ]);

            // Add checkbox symbols
            for ($row = 2; $row <= $lastRow; $row++) {
                $sheet->setCellValue('G' . $row, '☐');
            }
        }

        // Auto-size columns
        foreach (range('A', 'H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(30);

        return $sheet;
    }

    public static function download($format = 'xlsx')
    {
        $filename = 'score_criteria_' . date('Y-m-d_H-i-s') . '.' . $format;
        
        if ($format === 'pdf') {
            $criteria = ScoreCriteria::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name->en')
                ->get();
            
            $pdf = Pdf::loadView('exports.score-criteria-pdf', compact('criteria'));
            return $pdf->download($filename);
        }
        
        return Excel::download(new self($format), $filename);
    }
}
