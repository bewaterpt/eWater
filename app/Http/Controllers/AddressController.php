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

        if ($request->get('query')) {
            $query = $request->get('query');

            // dd($query);

            $localities = Locality::where('municipality_id', 14021)->get();

            $streets = Street::whereIn('locality_id', $localities->pluck('id'))
                ->where('streets.artery_title', 'like', "%{$query}%")
                ->where('streets.artery_designation', 'like', "%{$query}%")
                // ->distinct()
                // ->join("localities", "localities.id", "=", "streets.locality_id")
                // ->where('localities.name', 'like', "%{$query}%")
                ->get();

            dd($streets->map(function ($street) {
                return $street->locality->municipality->id;
            }));

            $output = '<ul class="dropdown-menu"
                style="display: block;
                position: relative;">';

            // foreach ($streets as $street) {
            //     $output .= '<li><a href="#">' . $street->artery_type . " " . $street->primary_preposition
            //         . " " . $street->artery_title . " " .
            //         $street->secondary_preposition . " " .
            //         $street->artery_designation . " " .
            //         $street->section . " "
            //         . $street->locality_name .
            //         '</a></li>';
            // }
            $output .= '</ ul>';
            dd($output);
            return json_encode($output);
        }
    }
}
