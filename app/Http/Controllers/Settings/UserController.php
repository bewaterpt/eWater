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
        $users = User::all()->map(function ($item) {
            $item->id = $this->encodeId($item->id);

            return $item;
        });
        return view('settings.users.index', ['users' => $users]);
    }

    public function delete(Request $request) {
        $userToDelete = User::find($this->decodeId($request->route('id')))->first();
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
        $user = User::find($this->decodeId($userId))->first();
        $user->id = $this->encodeId($user->id);

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

    public function edit($userId) {
        $user = User::find($this->decodeId($userId))->first();
        $user->id = $this->encodeId($user->id);
    }

    public function toggle_state($userId) {
        $user = User::find($this->decodeId($userId))->first();

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
