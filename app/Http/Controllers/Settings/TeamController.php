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
        $team->color = $request->color;
        $team->save();

        return redirect(route('settings.teams.list'));
    }

    public function update(Request $request) {
        $team = Team::find($request->id);
        $team->name = $request->name;
        $team->color = $request->color;
        $team->save();

        return redirect(route('settings.teams.list'));
    }

    public function delete(Request $request) {
        $team = Team::find($request->id);

        $team->users()->sync([]);
        $team->delete();

        return redirect(route('settings.teams.list'));
    }

    public function getTeamUsers(Request $request) {
        $data = [
            'status' => 500,
            'msg' => 'Unexpected error',
        ];

        $tableH = "
            <div class='table-responsive'>
                <table class='table table-sm table-bordered table-striped'>
                    <thead class='thead-light'>
                        <tr>
                            <th>" . trans('general.name') . "</th>
                            <th class='text-center actions'><i class='fas fa-tools text-black'></th>
                        </tr>
                    </thead>
                    <tbody>
        ";

        $tableF = "
                    </tbody>
                </table>
            </div>
        ";

        $team = Team::find($request->json('id'));

        if ($team) {
            $data['status'] = 200;
            $data['msg'] = 'Success';
            $data['content'] = $tableH;
            $users = $team->users()->get()->map(function($user) use($data) {
                return "<tr>
                            <td>" . $user->name . "</td>
                            <td class='actions text-center'>
                                <a data-id='" . $user->id . "' class='btn-link dissossiate-user text-danger' href='#'>
                                    <i class='fas fa-times'></i>
                                </a>
                            </td>
                        </tr>";
            });

            foreach($users as $user) {
                $data['content'] .= $user;
            }

            $data['content'] .= $tableF;

        } else {
            $data['status'] = 404;
            $data['msg'] = 'Not Found';
        }

        return json_encode($data);
    }
}
