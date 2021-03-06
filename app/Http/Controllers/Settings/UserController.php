<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use Route;
use App\Models\Role;
use App\Models\Team;

class UserController extends Controller
{

    public function index(Request $request) {
        $users = User::all()->sortBy('name');
        return view('settings.users.index', ['users' => $users]);
    }

    public function delete(Request $request) {
        $userToDelete = User::find($request->route('id'))->first();
        $currentUser = Auth::user();

        if (!$userToDelete) {
            return redirect()->back()->withErrors(__('settings.no_user_specified'), 'custom');
        }

        if ($userToDelete->id === 1) {
            return redirect()->back()->withErrors(__('settings.cant_delete_local_admin'), 'custom');
        }

        if ($userToDelete->id === $currentUser->id) {
            return redirect()->back()->withErrors(__('settings.cant_delete_self'), 'custom');
        }


        $userToDelete->delete();
        return redirect()->back()->with(['success', trans('settings.user_deleted')]);
    }

    public function view($userId) {
        $user = User::find($userId);

        return view('settings.users.view')->with([
            'user' => $user
        ]);
    }

    public function edit_self() {
        $user = Auth::user();

        return view('settings.users.edit_self', ['user' => $user]);
    }

    public function edit($userId) {
        $user = User::find($userId);
        $userRoleIds = $user->roles()->pluck('id');
        $userTeamIds = $user->teams()->pluck('id');

        $teams = Team::whereNotIn('id', $userTeamIds)->get();
        $roles = Role::whereNotIn('id', $userRoleIds)->get();

        return view('settings.users.edit', ['user' => $user, 'roles' => $roles, 'teams' => $teams]);
    }

    public function update(Request $request, $userId) {
        $user = User::find($userId);
        $user->name = $request->input('name');

        if ($request->has('accountable')) {
            $user->accountable = true;
        } else {
            $user->accountable = false ;
        }

        $user->save();

        $roleIds = [];
        if ($request->input('roles')) {
            $roleIds = explode(', ', $request->input('roles'));
        }

        $teamIds = [];
        if ($request->input('teams')) {
            $teamIds = explode(', ', $request->input('teams'));
        }

        $user->roles()->sync($roleIds);
        $user->teams()->sync($teamIds);

        return redirect()->back();
    }

    public function toggleState($userId) {
        $user = User::find($userId);

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
