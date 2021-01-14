<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Support\Str;
use App\User;
use App\Models\Role;
Use Auth;

class Permission extends Model
{
    public function roles() {
        return $this->belongsToMany('App\Models\Role');
    }

    public function can($route, $userId = null, $like = false) {
        $user = null;
        if($userId) {
            $user = User::find($userId);
        } else {
            $user = Auth::user();
        }

        if(!$user) {
            return redirect('/')->withErrors(__('auth.no_login'), 'custom');
        }

        if ($user->isAdmin()) {
            return true;
        }

        $roles = $user->roles()->get();



        if (sizeof($roles) <= 0) {
            return redirect('/')->withErrors(__('auth.no_roles'), 'custom');;
        }

        $permissions = [];
        foreach($roles as $role) {
            $permissions[] = $role->permissions()->pluck('route');
        }

        foreach ($permissions as $permissionSet) {
            foreach ($permissionSet as $permission) {
                if (Str::contains($permission, $route)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function roleCanAccess($role, $route, $like = false) {
        if(!$role) {
            return redirect('/')->withErrors(__('errors.generic'), 'custom');
        }

        if ($role->permissions()->pluck('route')->contains($route)) {
            return true;
        }

        return false;
    }

    public function getByRoute($route, $like = false) {
        return $like ? $this->where('route', $route)->first() : $this->where('route', 'like', $route)->get();
    }

    public function existsByRoute($route) {
        return $this->where('route', $route)->count() > 0 ? true : false;
    }
}
