<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public function roles() {
        return $this->belongsToMany('App\Models\Role');
    }

    public function can($route, $like = false) {
        $user = Auth::user();
        $permissions = $user->roles()->permissions()->get()->toArray();

        dd($permissions);

        if (sizeof($roles)) {
            return false;
        }

        if ($group->type !== 'superadmin') {
            if ($like) {
                $res = $this->where('group_id', $group_id)
                    ->where('route', 'like', $route.'%')
                    ->where('allow', 1)
                    ->first();
            }
            else {
                $res = $this->where('group_id', $group_id)
                    ->where('route', $route)
                    ->where('allow', 1)
                    ->first();
            }
        }
        else {
            return true;
        }

        if ($res) {
            return true;
        }
        else {
            return false;
        }
    }
}
