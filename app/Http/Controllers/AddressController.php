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

            // dd($query);

            $localities = Locality::where('municipality_id', 14021)->get();

            $streets = Street::selectRaw('id, full_street_designation as "searchable", "\\\\App\\\\Models\\\\Street" as "class"')
                ->whereIn('locality_id', $localities->pluck('id'))
                ->where('full_street_designation', 'like', sprintf('%%%s%%', $query))
                // ->whereRaw('CHAR_LENGTH(CONCAT(streets.artery_type, streets.primary_preposition, streets.artery_title, streets.secondary_preposition, streets.artery_designation, streets.section)) > 0')
                ->distinct();

            $localities = Locality::selectRaw('id, name as "searchable", "\\\\App\\\\Models\\\\Locality" as "class"')
                ->whereIn('id', $localities->pluck('id'))
                ->where('name', 'like', sprintf('%%%s%%', $query))
                ->union($streets);

            $results = $localities->get();
            // ->toSql();
            // dd($streets);

            $output = "";

            $results->map(function ($result) use (&$output) {
                $output .= "<li><a href='#' data-class='" . $result->class . "' data-resource-id='" . $result->id . "'>" . trim($result->searchable) . '</a></li>';
            });

            // dd($output);
            // $output['html'] = htmlentities($output['html']);

            return $output;
        }
    }
}
