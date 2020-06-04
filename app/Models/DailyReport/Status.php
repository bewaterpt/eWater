<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Status extends Model
{
    public function userCanApprove(Int $statusId) {
        $status = self::find($statusId);
        $user = Auth::user();

        if($status) {

        }

        $statusRoles = $status->roles()->get()->pluck('id')->toArray();
        $statusRolesSize = Sizeof($statusRoles);
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
