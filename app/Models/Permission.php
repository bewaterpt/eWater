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
            array_push($permissions, $role->permissions()->pluck('route'));
        }

        if (sizeof($roles) <= 0) {
            return false;
        }
        if ($user->roles()->pluck('slug')->contains('superadmin')) {
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

    public function getByRoute($route, $like = false) {
        return $like ? $this->where('route', $route)->first() : $this->where('route', 'like', $route)->get();
    }

    public function existsByRoute($route) {
        return $this->where('route', $route)->count() > 0 ? true : false;
    }
}
