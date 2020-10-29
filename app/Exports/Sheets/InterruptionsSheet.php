<?php
namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Interruption;

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

        // dd($this->limit);

        // dd(Interruption::all()
        // ->where('scheduled', $this->scheduled)
        // ->sortByDesc('id')
        // ->take($this->limit)->prepend($inter)->toArray());

        return Interruption::all()
            ->where('scheduled', $this->scheduled)
            ->sortByDesc('id')
            ->take($this->limit)->prepend($inter)->toArray();
    }

    /**
     * @return string
     */
    public function title(): string
    {
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
