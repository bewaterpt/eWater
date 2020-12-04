<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Carbon;
use App\Models\Yealink\CDRRecord;
use DB;
class CDRRecordExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private $searchCols;

    public function __construct($searchCols) {
        
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return DB::table('cdr_records')->orderBy('timestart');
    }

    public function map($cdr): array {
        return [
            Carbon::parse($cdr->timestart)->format('Y-m-d'),
            Carbon::parse($cdr->timestart)->format('H:i:s'),
            $cdr->type,
            $cdr->status,
            $cdr->callto,
            $cdr->callfrom,
            $cdr->waitduration,
            $cdr->talkduration,
            $cdr->callduration,
            '',
        ];
    }

    public function columnFormats(): array {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'B' => NumberFormat::FORMAT_DATE_TIME4,
        ];
    }

    public function headings(): array {
        return [
            __('general.date'),
            __('general.hour'),
            __('general.direction'),
            __('general.state'),
            __('calls.extension'),
            __('calls.remote'),
            __('calls.wait_duration'),
            __('calls.talk_duration'),
            __('calls.call_duration'),
            __('general.observations'),
        ];
    }
}
