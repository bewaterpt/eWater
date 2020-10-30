<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Interruption;
use App\Exports\InterruptionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory as REF;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory as WEF;

class ExportInterruptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interruptions:export {amount?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exports interruptions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // $inter = [];
        // $path = storage_path('app').'/templates/Master.xlsx';

        // $reader = REF::createReaderFromFile($path);
        // $reader->setShouldFormatDates(true); // this is to be able to copy dates
        // $reader->open($path);

        // foreach ($reader->getSheetIterator() as $sheetIndex => $sheet) {
        //     foreach ($sheet->getRowIterator() as $rowIndex => $row) {

        //         if ($rowIndex === 2) {
        //             $cells = $row->getCells();

        //             $inter[$sheetIndex][] = [
        //                 'start_date' => $cells[0]->getValue(),
        //                 'affected_area' => $cells[1]->getValue(),
        //                 'reinstatement_date' => $cells[2]->getValue(),
        //             ];
        //         }

        //     }

        //     $newInters = $sheetIndex > 1 ? Interruption::where('scheduled', true)->take(10)->get() : Interruption::where('scheduled', false)->take(10)->get();

        //     foreach ($newInters as $newInter) {
        //         $inter[$sheetIndex][] = [
        //             'start_date' => $newInter->start_date,
        //             'affected_area' => $newInter->affected_area,
        //             'reinstatement_date' => $newInter->reinstatement_date,
        //         ];
        //     }
        // }

        // dd($inters);

        Excel::store(new InterruptionsExport(10), 'comunicados.xls', 'interruptions', \Maatwebsite\Excel\Excel::XLS);
        shell_exec('python '.base_path('scripts/scpInterruptionsToWebsite.py'));
    }
}
