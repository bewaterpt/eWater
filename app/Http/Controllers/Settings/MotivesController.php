<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InterruptionMotive as Motive;
use App\User;
use Mail;
use Auth;
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
        $type = '';
        $scheduled = false;

        if (
            $this->currentUser->countRoles(['ewater_interrupcoes_programadas_criacao', 'ewater_interrupcoes_programadas_edicao']) > 0 &&
            $this->currentUser->hasRoles(['ewater_interrupcoes_nao_programadas']) === false
        ) {
            $type = mb_strtolower(__('general.interruptions.is_scheduled'));
            $scheduled = true;
        } else if (
            $this->currentUser->hasRoles(['ewater_interrupcoes_nao_programadas']) &&
            $this->currentUser->countRoles(['ewater_interrupcoes_programadas_criacao', 'ewater_interrupcoes_programadas_edicao']) == 0
        ) {
            $type = mb_strtolower(__('general.interruptions.is_unscheduled'));
        }

        return view('settings.motives.create', ['type' => $type, 'scheduled' => $scheduled]);
    }
    /**
     * Stores the motive in the database
     *
     * @param Request $request The request data
     *
     * @return \Illuminate\Http\Client\Response Redirects user to motive list
     */
    public function store(Request $request) {

        $scheduled = $request->scheduled == 'true' ? true : false;
        $motive = new Motive();
        $motive->name = $request->name;
        $motive->slug = $request->slug;
        $motive->scheduled = $scheduled;
        $motive->save();


        return redirect(route('settings.motives.list'))->with('success');
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
