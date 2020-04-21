<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use Route;

class UserController extends Controller
{

    public function index(Request $request) {
        $users = User::all();
        $user_arr = [];

        foreach($users as $user) {
            $roles = DB::table('role_user')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('user_id', $user->id)
            ->get('name');

            array_push($user_arr,
                [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'roles' => $roles,
                    'enabled' => $user->enabled,
                ]
            );
        }

        return view('settings.users.index', ['users' => $user_arr]);
    }

    public function delete(Request $request) {
        $userToDelete = User::find($request->route('id'));
        $current_user = Auth::user();

        if (!$userToDelete) {
            return redirect()->back()->with(['error', trans('settings.no_user_specified')]);
        }

        if ($userToDelete->id === 1) {
            return redirect()->back()->with(['error', trans('settings.cant_delete_local_admin')]);
        }

        if($userToDelete->id === $current_user->id) {
            return redirect()->back()->with(['error', trans('settings.cant_delete_self')]);
        }


        $userToDelete->delete();
        return redirect()->back()->with(['success', trans('settings.user_deleted')]);
    }

    public function view(User $user) {
        return view('settings.users.view')->with([
            'user' => $user->first()
        ]);
    }

    public function edit_self() {
        $user = Auth::user();

        return view('settings.users.edit_self',
        [
            'user' => $user,
        ]);
    }

    public function edit(User $user) {

    }

    public function toggle_state(User $user) {
        // $user
    }
}
