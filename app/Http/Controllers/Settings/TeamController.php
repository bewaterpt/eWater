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

    public function create() {
        return view('settings.teams.create');
    }

    public function edit(Request $request) {
        $team = Team::find($request->id);

        return view('settings.teams.edit', ['team' => $team]);
    }

    public function store(Request $request) {
        $team = new Team();
        $team->name = $request->name;
        $team->save();

        return redirect(route('settings.teams.list'));
    }

    public function update(Request $request) {
        $team = Team::find($request->id);
        $team->name = $request->name;
        $team->save();

        return redirect(route('settings.teams.list'));
    }

    public function delete(Request $request) {
        $team = Team::find($request->id);

        $team->users()->update(['team_id' => null]);
        $team->delete();

        return redirect(route('settings.teams.list'));
    }
}
