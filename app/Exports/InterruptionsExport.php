<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\InterruptionsSheet;

class InterruptionsExport implements WithMultipleSheets
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
}
