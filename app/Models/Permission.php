<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

        $roles = $user->roles()->get();

        if (sizeof($roles) <= 0) {
            return false;
        }

        $permissions = [];

        foreach($roles as $role) {
            array_push($permissions, $role->permissions()->pluck('route'));
        }

        if ($user->roles()->pluck('slug')->contains('admin')) {
            return true;
        } else {
            foreach ($permissions as $permission) {
                if ($permission->contains($route)) {
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
