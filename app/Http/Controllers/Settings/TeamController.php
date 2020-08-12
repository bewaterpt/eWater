<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $teams = Team::all();

        return view('settings.teams.index', ['teams' => $teams]);
    }
}
