<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InterruptionMotive as Motive;

class MotivesController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Presents a list of Interruption Motives
     *
     * @param Request $request The request data
     *
     * @return \Illuminate\View\Factory View ID/Name: settings.motives.index
     */
    public function index(Request $request) {
        if ($request->ajax()) {

        }

        return view('settings.motives.index');
    }

    /**
     * Presents the motive creation page
     *
     * @return \Illuminate\View\Factory View ID/Name: settings.motives.create
     */
    public function create() {
        return view('settings.motives.create');
    }

    /**
     * Stores the motive in the database
     *
     * @param Request $request The request data
     *
     * @return \Illuminate\Http\Client\Response Redirects user to motive list
     */
    public function store(Request $request) {
    }

    /**
     * Presents the motive edit page
     *
     * @return \Illuminate\View\Factory View ID/Name: settings.motives.edit
     */
    public function edit() {
        return view('settings.motives.edit');
    }

    /**
     * Updates a motive in the database
     *
     * @param Request $request The request data
     *
     * @return \Illuminate\Http\Client\Response Redirects user to motive list
     */
    public function update(Request $request) {
    }
}
