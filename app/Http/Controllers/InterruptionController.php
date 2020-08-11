<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interruption;

class InterruptionController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {
        $interruptions = Interruption::all();

        return view('interruptions.index', ['interruptions' => $interruptions]);
    }
}
