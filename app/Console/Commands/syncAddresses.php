<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\District;
use App\Models\Municipality;
use App\Models\Street;
use DB;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory as REF;

class syncAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addresses:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        //script Python para ler o ficheiro com Moradas
        // shell_exec('cd ' . base_path('scripts/postal_codes') . '; make scrape');
        // $rows = Excel::toCollection([], storage_path('app') . '/temp/codigos_postais.csv');
        // foreach ($rows as $row) {
        //     Street::insert($row->toArray());
        //     dd($row);
        // }

        //Distritos
        // $index = 0;
        // $tempFile = storage_path('app').'/temp/distritos.csv';
        // $reader = REF::createReaderFromFile($tempFile);
        // $reader->open($tempFile);
        // foreach ($reader->getSheetIterator() as $sheet) {
        //     foreach ($sheet->getRowIterator() as $row) {
        //         if ($index !== 0) {
        //             $cells = $row->getCells();

        //             $districts[] = [
        //                 'district_code' => $cells[0]->getValue(),
        //                 'designation'=> $cells[1]->getValue(),
        //             ];

        //             unset($cells, $row);
        //             gc_collect_cycles();
        //             $index++;
        //         }else {
        //             $index++;
        //         }

        //     }
        // }
        // $districts = collect($districts);
        // if ($districts->count() > 0) {
        //     $this->info('Inserting records in the database');

        //     foreach ($districts->chunk(200) as $district) {
        //         District::insert($district->toArray());

        //     }
        // } else {
        //     $this->info('Nothing to insert in the database');
        // }

        //Municipios
        $index = 0;
        $tempFile = storage_path('app').'/temp/concelhos.csv';
        $reader = REF::createReaderFromFile($tempFile);
        $reader->open($tempFile);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if ($index !== 0) {
                    $cells = $row->getCells();

                    $municipalities[] = [
                        'designation'=> $cells[2]->getValue(),
                        'district_code'=> $cells[0]->getValue(),
                        'municipality_code' => $cells[1]->getValue(),
                    ];

                    unset($cells, $row);
                    gc_collect_cycles();
                    $index++;
                }else {
                    $index++;
                }

            }
        }
        $municipalities = collect($municipalities);
        if ($municipalities->count() > 0) {
            $this->info('Inserting records in the database');
            foreach ($municipalities->chunk(200) as $municipalitiesChunk ) {
                $builder = DB::table((new Municipality)->getTable());
                dd($builder->getGrammar()->compileInsert($builder, $municipalitiesChunk->toArray()));
            }
        } else {
            $this->info('Nothing to insert in the database');
        }

        //Moradas
        $index = 0;
        $tempFile = storage_path('app').'/temp/codigos_postais.csv';
        $reader = REF::createReaderFromFile($tempFile);
        $reader->open($tempFile);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if ($index !== 0) {
                    $cells = $row->getCells();

                    dd();

                    $addresses[] = [
                        'district_code' => $cells[0]->getValue(),
                        'municipality_code'=> $cells[1]->getValue(),
                        'locality_code'=> $cells[2]->getValue(),
                        'locality_name'=> $cells[3]->getValue(),
                        'artery_code'=> (int)$cells[4]->getValue(),
                        'artery_type'=> $cells[5]->getValue(),
                        'primary_preposition'=> $cells[6]->getValue(),
                        'artery_title'=> $cells[7]->getValue(),
                        'secondary_preposition'=> $cells[8]->getValue(),
                        'artery_designation'=> $cells[9]->getValue(),
                        'section'=> $cells[11]->getValue(),
                        'door_number'=> $cells[12]->getValue(),
                        'client_name'=> $cells[13]->getValue(),
                        'postal_code'=> $cells[14]->getValue(),
                        'postal_code_extension'=> $cells[15]->getValue(),
                        'postal_designation'=> $cells[16]->getValue(),
                    ];

                    unset($cells, $row);
                    gc_collect_cycles();
                    $index++;
                }else {
                    $index++;
                }

            }
        }
        $addresses = collect($addresses);
        if ($addresses->count() > 0) {
            $this->info('Inserting records in the database');

            foreach ($addresses->chunk(200) as $address) {
                Street::insert($address->toArray());

            }
        } else {
            $this->info('Nothing to insert in the database');
        }
    }
}


