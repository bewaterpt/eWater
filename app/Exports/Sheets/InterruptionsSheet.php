<?php
namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Interruption;
use Illuminate\Support\Carbon;

class InterruptionsSheet implements FromArray, WithTitle, WithMapping, WithHeadings
{
    private $name;
    private $scheduled;
    private $limit;

    public function __construct(string $name, bool $scheduled, int $limit)
    {
        $this->name = $name;
        $this->scheduled = $scheduled;
        $this->limit = $limit;
    }

    /**
     * @return Builder
     */
    public function array(): array {
        // $inter = [
        //     'scheduled' => '',
        //     'start_date' => '',
        //     'affected_area' => '',
        //     'reinstatement_date' => '',
        // ];

        $int = Interruption::select('scheduled', 'start_date', 'affected_area', 'reinstatement_date')->where('scheduled', $this->scheduled)->where('start_date', '>', Carbon::now()->subMonth(1)->format('y-m-d H:i:s'));
        $int = $int->orderBy('updated_at', 'desc')->orderBy('created_at', 'desc');
        // $this->scheduled ? $int->whereBetween('start_date', [Carbon::now()->subHours(48)->format('y-m-d H:i:s'), Carbon::now()->format('y-m-d H:i:s')]) : null ;


        $int = $int->take($this->limit)->get()->toArray();

        return $int;
    }

    /**
     * @return string
     */
    public function title(): string {
        return $this->name;
    }

    public function map($interruption): array {
        return [
            $interruption['scheduled'],
            $interruption['start_date'],
            strip_tags($interruption['affected_area']),
            $interruption['reinstatement_date']
        ];
    }

    public function headings(): array {
        return [
            'scheduled',
            'DataInicio',
            'AreaAfectada',
            'DataRestabelecimento',
        ];
    }
}
