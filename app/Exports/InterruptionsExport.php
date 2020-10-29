<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Exports\Sheets\InterruptionsSheet;

class InterruptionsExport implements WithMultipleSheets, WithColumnFormatting
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $limit = false;

    public function __construct(int $limit) {
        $this->limit = $limit;
    }

    public function sheets(): array {
        $sheets = [
            new InterruptionsSheet('InterrupcoesNaoProg', false, $this->limit),
            new InterruptionsSheet('InterrupcoesProg', true, $this->limit),
        ];

        return $sheets;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_GENERAL,
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'F' => NumberFormat::FORMAT_TEXT,
            'G' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
            'I' => NumberFormat::FORMAT_GENERAL,
            'J' => NumberFormat::FORMAT_GENERAL,
            'K' => NumberFormat::FORMAT_GENERAL,
            'L' => NumberFormat::FORMAT_GENERAL,
            'M' => NumberFormat::FORMAT_GENERAL,
            'N' => NumberFormat::FORMAT_GENERAL,
            'O' => NumberFormat::FORMAT_GENERAL,
            'P' => NumberFormat::FORMAT_GENERAL,
            'Q' => NumberFormat::FORMAT_GENERAL,
            'R' => NumberFormat::FORMAT_GENERAL,
            'S' => NumberFormat::FORMAT_GENERAL,
            'T' => NumberFormat::FORMAT_GENERAL,
            'U' => NumberFormat::FORMAT_GENERAL,
            'V' => NumberFormat::FORMAT_GENERAL,
            'W' => NumberFormat::FORMAT_GENERAL,
            'X' => NumberFormat::FORMAT_GENERAL,
            'Y' => NumberFormat::FORMAT_GENERAL,
            'Z' => NumberFormat::FORMAT_GENERAL,
        ];
    }
}
