<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentController extends Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {
        $agents = Agent::all();

        return view('settings.agents.index', ['agents' => $agents]);
    }

    public function delete(Request $request) {
        $userToDelete = User::find($request->route('id'));
        $current_user = Auth::user();

        if (!$userToDelete) {
            return redirect()->back()->withErrors(__('settings.no_user_specified'), custom);
        }

        if ($userToDelete->id === 1) {
            return redirect()->back()->withErrors(__('settings.cant_delete_local_admin'), 'custom');
        }

        if ($userToDelete->id === $current_user->id) {
            return redirect()->back()->withErrors(__('settings.cant_delete_self'), 'custom');
        }


        $userToDelete->delete();
        return redirect()->back()->with(['success', trans('settings.user_deleted')]);
    }

    public function view($user_id) {
        return view('settings.users.view')->with([
            'user' => $user->first()
        ]);
    }

    public function edit($user_id) {

    }

    public function toggle_state($user_id) {
        $user = User::find($user_id);

        if (!$user) {
            return redirect()->back()->withErrors(__('settings.user_doesnt_exist'), 'custom');
        }

        if ($user->enabled()) {
            $user->disable();
        } else {
            $user->enable();
        }

        return redirect()->back()->with('success');
    }
}
