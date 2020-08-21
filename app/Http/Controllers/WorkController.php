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
        return json_encode(OutonoObras::exists($request->json('id')));
    }
}
