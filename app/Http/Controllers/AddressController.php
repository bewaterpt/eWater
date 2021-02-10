<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Municipality;
use App\Models\Locality;
use App\Models\Street;

class AddressController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function autocomplete(Request $request){

        if($request->get('query')){
            $query = $request->get('query');

            $streets = Street::where('streets.municipality_code', 21)
                        ->where('streets.artery_title', 'like', "%{$query}%")
                        ->where('streets.artery_designation', 'like', "%{$query}%")
                        ->distinct()
                        ->join("localities", "localities.locality_code", "=", "streets.locality_code")
                        ->where('localities.locality_name', 'like', "%{$query}%")
                        ->get();

            $output = '<ul class="dropdown-menu"
                style="display: block;
                position: relative;">';

            foreach($streets as $street) {
                $output .= '<li><a href="#">'.$street->artery_type." ".$street->primary_preposition
                ." ".$street->artery_title." ".
                $street->secondary_preposition." ".
                $street->artery_designation." ".
                $street->section." "
                .$street->locality_name.
                '</a></li>';
            }
            $output .= '</ ul>';
            dd($output);
            return json_encode($output);
        }
    }
}
