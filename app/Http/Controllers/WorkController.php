<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Connectors\OutonoObras;

class WorkController extends Controller
{

    public function __contruct() {
        parent::__construct();
    }

    public function workExists(Request $request) {
        $data = [
            'reason' => 'Unknown',
            'value' => true,
        ];

        $work = OutonoObras::find($request->json('id'));
        //::find($request->json('id'));

        if(!$work) {
            $data['value'] = false;
            $data['reason'] = 'not-found';
        } else {
            $data['value'] = $work->active();
            $data['reason'] = $work->executionStateText();
        }

        return json_encode($data);
    }
}
