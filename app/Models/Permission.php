<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
Use Auth;

class Permission extends Model
{
    public function roles() {
        return $this->belongsToMany('App\Models\Role');
    }

    public function can($route, $like = false) {
        $user = Auth::user();
        $roles = $user->roles()->get();
        $permissions = [];

        if(!$user) {
            return redirect('/')->withErrors(__('auth.no_login'), 'custom');
        }

        foreach($roles as $role) {
            echo $role->id;
            echo "\n";
            array_push($permissions, $role->permissions()->pluck('route'));
        }

        // dd($permissions);

        if (sizeof($roles) <= 0) {
            return false;
        }

        // if ($current_route == 'settings.user.edit') {
        //     if ($user->id == User::find($request->route('id'))->first()->id) {
        //         return true;
        //     }
        // }

        if ($user->roles()->pluck('slug')->contains('superadmin')) {
            return true;
        } else {
            foreach ($permissions as $permission) {
                echo $permission;
                echo "\n";
                if ($permission->contains($route)) {
                    dd($route);
                    return true;
                }
            }
        }
        die;
        return false;
    }
}
