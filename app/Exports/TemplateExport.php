<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TemplateExport implements WithMultipleSheets
{
    protected $templateData;

    public function __construct(array $templateData)
    {
        $this->templateData = $templateData;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        foreach ($this->templateData as $sheetName => $data) {
            $sheets[$sheetName] = new TemplateSheet($data);
        }
        
        return $sheets;
    }
}

class TemplateSheet implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        // Remove the first row (headings) from data since we'll use WithHeadings
        return array_slice($this->data, 1);
    }

    public function headings(): array
    {
        return $this->data[0] ?? [];
    }
}
