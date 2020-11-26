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
        $inter = [
            'scheduled' => '',
            'start_date' => '',
            'affected_area' => '',
            'reinstatement_date' => '',
        ];

        // dd(Carbon::now()->subSeconds(172800*2)->format('Y-m-d H:i:s'));

        // dd(
        //     Interruption::select('*')
        //     ->where('scheduled', $this->scheduled)
        //     ->whereBetween('start_date', [Carbon::now()->subSeconds(172800*2)->format('Y-m-d H:i:s'), Carbon::now()->format('Y-m-d H:i:s')])
        //     ->orderByDesc('id')
        //     // ->take($this->limit)
        //     // ->get()
        //     ->toSql()
        //     // ->prepend($inter)
        //     // ->toArray()
        // );

        // dd(Interruption::all()
        // ->where('scheduled', $this->scheduled)
        // ->sortByDesc('id')
        // ->take($this->limit)->prepend($inter)->toArray());

        $int = Interruption::all()
        ->where('scheduled', $this->scheduled)
        ->where('start_date', '>', Carbon::now()->subMonth(1)->format('y-m-d H:i:s'));

        // $this->scheduled ? $int->whereBetween('start_date', [Carbon::now()->subSeconds(172800), Carbon::now()]) : null ;

        $int = $int->sortByDesc('id')
        ->take($this->limit)
        ->prepend($inter)
        ->toArray();

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
