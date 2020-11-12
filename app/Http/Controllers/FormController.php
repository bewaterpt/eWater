<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index() {

    }

    public function create() {
        return view('settings.forms.create');
    }

    public function store(Request $request) {
        dd($request->input());
        return redirect();
    }
}
