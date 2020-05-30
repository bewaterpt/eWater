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
        return view('settings.users.index', ['users' => User::all()]);
    }

    public function delete(Request $request) {
        $userToDelete = User::find($request->route('id'));
        $current_user = Auth::user();

        if (!$userToDelete) {
            return redirect()->back()->withErrors(__('settings.no_user_specified'), 'custom');
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
        $user = User::find($this->decodeId($user_id));
        return view('settings.users.view')->with([
            'user' => $user
        ]);
    }

    public function edit_self() {
        $user = Auth::user();

        return view('settings.users.edit_self',
        [
            'user' => $user,
        ]);
    }

    public function edit($user_id) {
        $user = User::find($user_id);
    }

    public function toggle_state($userId) {
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
