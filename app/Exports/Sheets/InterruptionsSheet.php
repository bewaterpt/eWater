<?php
namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Interruption;

class InterruptionsSheet implements FromCollection, WithTitle, WithMapping, WithHeadings
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
    public function collection()
    {

        // dd($this->limit);

        return Interruption::all()
            ->where('scheduled', $this->scheduled)
            ->sortByDesc('id')
            ->take($this->limit);
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
            $interruption->start_date,
            strip_tags($interruption->affected_area),
            $interruption->reinstatement_date
        ];
    }

    public function headings(): array {
        return [
            'DataInicio',
            'AreaAfectada',
            'DataRestabelecimento',
        ];
    }
}
