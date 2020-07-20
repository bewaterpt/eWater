<?php

namespace App\Models\DailyReports;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Status extends Model
{
    public function userCanProgress($status = false) {

        if ($status) {
            $status = self::find($status);
        } else {
            $status = $this;
        }

        $user = Auth::user();

        $statusRoles = $status->roles()->get()->pluck('id')->toArray();
        $statusRolesSize = Sizeof($statusRoles);

        if($statusRolesSize <= 0) {
            return false;
        }

        $userRoles = $user->roles()->get()->pluck('id')->toArray();

        if (Sizeof(array_diff($statusRoles, $userRoles)) !== $statusRolesSize) {
            return true;
        } else {
            return false;
        }
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Role');
    }
}
