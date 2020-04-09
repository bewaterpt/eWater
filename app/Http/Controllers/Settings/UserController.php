<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    public function index() {
        $users = DB::table('users')->get();
        $user_arr = [];
        foreach($users as $user) {
            $roles = DB::table('role_user')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('user_id', $user->id)
            ->get('name');

            array_push($user_arr,
                [
                    'name' => $user->name,
                    'username' => $user->username,
                    'roles' => $roles
                ]
            );
        }

        return view('settings.users.index', ['users' => (object)$user_arr]);
    }
}
