<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReports\Report;
use App\Models\Connectors\OutonoArtigos as Artigos;
use App\Models\Connectors\OutonoObrasCC as ObrasCC;
use Illuminate\Support\Carbon;
use App\Models\Forms\Form;
use Log;
use DB;
use Cache;
use Storage;
use App\Models\Article;
use App\Custom\Classes\FieldSet;
use App\Models\Interruption;

class TestController extends Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {
        // $input = collect($request->except('_token'));

        // $form = new Form($input->shift('form-name'), $input->shift('form-description'));
        // $fieldSet = [];

        // foreach ($input as $field => $values) {
        //     foreach ($values as $i => $value) {
        //     }
        // }

        // dd($fieldSet);

        return view('mail.interruptions.canceled', ['interruption' => Interruption::first(), 'delegation' => Interruption::first()->delegation()->first()]);
    }
}
