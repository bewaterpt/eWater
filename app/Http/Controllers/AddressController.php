<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipality;
use App\Models\Locality;
use App\Models\Street;

class AddressController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function autocomplete(Request $request)
    {

        if ($query = $request->get('query')) {

            $output = [
                'html' => '',
            ];

            // dd($query);

            $localities = Locality::where('municipality_id', 14021)->get();

            $streets = Street::whereIn('locality_id', $localities->pluck('id'))
                ->orWhere('streets.artery_title', 'like', "%{$query}%")
                ->orWhere('streets.artery_designation', 'like', "%{$query}%")
                ->distinct()
                ->get();

            $streets->map(function ($street) use (&$output) {
                $output['html'] .= "<li><a href='#'>" . $street->artery_type . " " . $street->primary_preposition
                    . " " . $street->artery_title . " " .
                    $street->secondary_preposition . " " .
                    $street->artery_designation . " " .
                    $street->section . " "
                    . $street->locality_name .
                    '</a></li>';
            });

            // dd($output);
            // $output['html'] = htmlentities($output['html']);

            return json_encode($output);
        }
    }
}
