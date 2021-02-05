<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\District;
use App\Models\Locality;
use App\Models\Municipality;
use App\Models\Street;
use DB;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory as REF;
use League\Flysystem\Adapter\Local;

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

        //Municipios
        $index = 0;
        $checker = false;
        $tempFile = storage_path('app').'/temp/concelhos.csv';
        $reader = REF::createReaderFromFile($tempFile);
        $reader->open($tempFile);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if ($index !== 0) {
                    $cells = $row->getCells();
                    //$str = ltrim($str, '0');
                    $municipalities[] = [
                        'designation'=> $cells[2]->getValue(),
                        'municipality_code' => $cells[1]->getValue(),
                        'id'=>intval(intval($cells[0]->getValue()) . $cells[1]->getValue()),
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
        $dbMunicipalities = Municipality::pluck('id')->toArray();

        if ($municipalities->count() != sizeof($dbMunicipalities)) {
            $this->info('Inserting records in the database');

            foreach ($municipalities->chunk(200) as $municipalitiesChunk ) {

                foreach ($municipalitiesChunk as $municipalityChunk){
                    $municipalityChunk = collect($municipalityChunk);
                    //

                    if(!in_array($municipalityChunk['id'],$dbMunicipalities)){

                        Municipality::insert($municipalityChunk->toArray());
                    }
                    else{
                        $checker = true;
                    }
                }
            }
        } else {
            $this->info('Nothing to insert in the database');
        }


        //Moradas
        $index = 0;
        $checker = false;
        $tempFile = storage_path('app').'/temp/codigos_postais.csv';
        $reader = REF::createReaderFromFile($tempFile);
        $reader->open($tempFile);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if ($index !== 0) {
                    $cells = $row->getCells();

                    $addresses[] = [
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
                        'municipality_code'=>intval($cells[1]->getValue()),
                        'municipality_id'=>intval(intval($cells[0]->getValue()) . $cells[1]->getValue()),
                    ];

                    unset($cells, $row);
                    gc_collect_cycles();
                    $index++;
                }else {
                    $index++;
                }

            }
        }
        $localities = [];
        $localities = collect($localities);
        $addresses = collect($addresses);


        foreach($addresses as $address){
            if (!isset($localities[$address['locality_code']])) {
                $localities[$address['locality_code']] = [
                    'municipality_id'=>$address['municipality_id'],
                    'municipality_code' => $address['municipality_code'],
                    'locality_name' => $address['locality_name'],
                    'locality_code' => $address['locality_code'],
                ];
            }
        };


        $dbLocalities = Locality::pluck('locality_code')->toArray();

        if ($localities->count() != sizeOf($dbLocalities)) {
            $this->info('Inserting records in the database');

            foreach ($localities->chunk(200) as $localitiesChunk) {

                foreach ($localitiesChunk as $localityChunk){
                    $localityChunk = collect($localityChunk);
                    //

                    if(!in_array($localityChunk['id'],$dbLocalities)){

                        Locality::insert($localityChunk->toArray());
                    }
                }
            }
        } else {
            $this->info('Nothing to insert in the database');
        }

        $dbAddresses = Street::pluck('id')->toArray();
        if ($addresses->count() != sizeOf($dbAddresses)) {
            $this->info('Inserting records in the database');

            foreach ($addresses->chunk(200) as $addressesChunk) {

                foreach ($localitiesChunk as $localityChunk){
                    $addressesChunk = collect($addressesChunk);
                    //

                    if(!in_array($addressesChunk['id'],$dbAddresses)){

                        Street::insert($addressesChunk->toArray());
                    }
                }

            }
        } else {
            $this->info('Nothing to insert in the database');
        }
    }
}


