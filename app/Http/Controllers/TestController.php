<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Interruption;
use App\Helpers\Helper;
use App\Helpers\CsvHelper;

class TestController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // $input = collect($request->except('_token'));

        // $form = new Form($input->shift('form-name'), $input->shift('form-description'));
        // $fieldSet = [];

        // foreach ($input as $field => $values) {
        //     foreach ($values as $i => $value) {
        //     }
        // }
        ini_set('max_execution_time', 2400);

        $postalCodesFile = storage_path('app') . '/temp/codigos_postais.csv';

        $csv = new CsvHelper($postalCodesFile);
        $itemArr = collect([]);
        $i = 0;

        $addresses = collect([]);
        $localities = collect([]);
        $csv->readFile(function ($item) use (&$addresses, &$localities){
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
                    'section' => $item->troco,
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

        dd($addresses);

        return view('mail.interruptions.canceled', ['interruption' => Interruption::find(1000), 'scheduled' => 'scheduled', 'carbon' => new Carbon, 'helpers' => new Helper, 'delegation' => Interruption::first()->delegation()->first(), 'translationString' => Interruption::first()->scheduled ? __('mail.interruptions.scheduled.created') : __('mail.interruptions.unscheduled.created')]);
        return view('tests.test');
    }
}
