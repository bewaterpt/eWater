<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Interruption;
use App\Helpers\Helper;

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

        // dd($fieldSet);
        return view('mail.interruptions.canceled', ['interruption' => Interruption::find(1000), 'scheduled' => 'scheduled', 'carbon' => new Carbon, 'helpers' => new Helper, 'delegation' => Interruption::first()->delegation()->first(), 'translationString' => Interruption::first()->scheduled ? __('mail.interruptions.scheduled.created') : __('mail.interruptions.unscheduled.created')]);
        return view('tests.test');
    }
}
