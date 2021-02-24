<?php

namespace App\Console\Commands;

use App\Helpers\CsvHelper;
use Illuminate\Console\Command;
use App\Models\Locality;
use App\Models\Municipality;
use App\Models\Street;
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
        // Run python scraper
        $this->info('Executing web scraper');
        shell_exec('cd ' . base_path('scripts/postal_codes') . '; make scrape');

        $index = 0;

        // Municipalities

        $municipalitiesFile = storage_path('app') . '/temp/concelhos.csv';
        $reader = REF::createReaderFromFile($municipalitiesFile);
        $reader->open($municipalitiesFile);

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                if ($index !== 0) {
                    $cells = $row->getCells();
                    //$str = ltrim($str, '0');
                    $municipalities[] = [
                        'name' => $cells[2]->getValue(),
                        'municipality_code' => $cells[1]->getValue(),
                        'id' => intval(intval($cells[0]->getValue()) . $cells[1]->getValue()),
                    ];

                    unset($cells, $row);
                    gc_collect_cycles();
                    $index++;
                } else {
                    $index++;
                }
            }
        }

        $this->info('Inserting municipalities in the database');
        $municipalities = collect($municipalities);
        $dbMunicipalities = Municipality::pluck('id')->toArray();
        $diff = array_diff($municipalities->pluck('id')->toArray(), $dbMunicipalities);

        if (sizeOf($diff) > 0) {
            $this->info('Chunking municipalities');

            $municipalities = $municipalities->whereIn('id', $diff);

            foreach ($municipalities->chunk(200) as $municipalityChunks) {
                foreach ($municipalityChunks as $municipalityChunk) {
                    Municipality::insert($municipalityChunk);
                }
            }
        } else {
            $this->info('No municipalities to insert');
        }

        $index = 0;

        // Localidades e Ruas
        $this->info('Reading postal codes file, this might take a while...');
        $postalCodesFile = storage_path('app') . '/temp/codigos_postais.csv';

        $csv = new CsvHelper($postalCodesFile);

        $addresses = collect([]);
        $localities = collect([]);
        $csv->readFile(function ($item) {
            $municipalityId = intval(intval($item->cod_distrito) . $item->cod_concelho);
            $municipalityCode = intval($item->cod_concelho);
            $localityCode = $item->cod_localidade;
            $localityId = intval($municipalityCode . $localityCode);

            if ($item->cod_arteria != "") {
                $addresses[] = [
                    'artery_code' => intval($item->cod_arteria),
                    'artery_type' => $item->tipo_arteria,
                    'primary_preposition' => $item->prep1,
                    'artery_title' => $item->titulo_arteria,
                    'secondary_preposition' => $item->prep2,
                    'artery_designation' => $item->nome_arteria,
                    'artery_location' => $item->local_arteria,
                    'section' => $item->tronco,
                    'door_number' => $item->porta,
                    'client_name' => $item->cliente,
                    'postal_code' => intval($item->num_cod_postal),
                    'postal_code_extension' => intval($item->ext_cod_postal),
                    'postal_designation' => $item->desig_postal,
                    'locality_id' => $localityId,
                ];
            } else {
                if (!isset($localities[$localityId])) {
                    $localities[$localityId] = [
                        'municipality_id' => $municipalityId,
                        'locality_code' => $localityCode,
                        'locality_name' => $item->nome_localidade,
                        'id' => $localityId,
                    ];
                }
            }
        });

        // $reader = REF::createReaderFromFile($postalCodesFile);
        // $reader->open($postalCodesFile);


        // foreach ($reader->getSheetIterator() as $sheet) {
        //     foreach ($sheet->getRowIterator() as $row) {
        //         if ($index !== 0) {
        //             $cells = $row->getCells();

        //             $municipalityId = intval(intval($cells[0]->getValue()) . $cells[1]->getValue());
        //             $municipalityCode = intval($cells[1]->getValue());
        //             $localityCode = $cells[2]->getValue();
        //             $localityId = intval($municipalityCode . $localityCode);

        //             $addresses[] = [
        //                 'artery_code' => (int)$cells[4]->getValue(),
        //                 'artery_type' => $cells[5]->getValue(),
        //                 'primary_preposition' => $cells[6]->getValue(),
        //                 'artery_title' => $cells[7]->getValue(),
        //                 'secondary_preposition' => $cells[8]->getValue(),
        //                 'artery_designation' => $cells[9]->getValue(),
        //                 'section' => $cells[11]->getValue(),
        //                 'door_number' => $cells[12]->getValue(),
        //                 'client_name' => $cells[13]->getValue(),
        //                 'postal_code' => $cells[14]->getValue(),
        //                 'postal_code_extension' => $cells[15]->getValue(),
        //                 'postal_designation' => $cells[16]->getValue(),
        //                 'locality_id' => $localityId,
        //             ];

        //             if (!isset($localities[$localityCode])) {
        //                 $localities[$localityCode] = [
        //                     'municipality_id' => $municipalityId,
        //                     'locality_code' => $localityCode,
        //                     'locality_name' => $cells[3]->getValue(),
        //                     'id' => $localityId,
        //                 ];
        //             }

        //             unset($cells, $row);
        //             gc_collect_cycles();
        //             $index++;
        //         } else {
        //             $index++;
        //         }
        //         echo $index . "\n";
        //     }
        // }

        // $localities->map(function ($locality) {
        //     shell_exec('cd /home/bruno/documents; echo ' . implode(',', $locality->toArray()) . ' >> localities.txt');
        // });

        $this->info('Inserting localities in the database');
        $dbLocalities = Locality::pluck('locality_code')->toArray();
        $diff = array_diff($localities->pluck('id')->toArray(), $dbLocalities);

        if (sizeOf($diff) > 0) {
            $this->info('Chunking localities');

            $localities = $localities->whereIn('id', $diff);

            foreach ($localities->chunk(200) as $localityChunks) {
                foreach ($localityChunks as $localityChunk) {
                    Locality::insert($localityChunk);
                }
            }
        } else {
            $this->comment('No localities to insert');
        }

        $this->info('Inserting addresses in the database');
        $dbAddresses = Street::pluck('id')->toArray();
        $diff = array_diff($addresses->pluck('id')->toArray(), $dbAddresses);

        if (sizeOf($diff) > 0) {

            $this->info('Chunking addresses');

            $addresses = $addresses->whereIn('id', $diff);

            foreach ($addresses->chunk(200) as $addressChunks) {
                foreach ($addressChunks as $addressChunk) {
                    Street::insert($addressChunk);
                }
            }
        } else {
            $this->comment('No addresses to insert');
        }
    }
}
