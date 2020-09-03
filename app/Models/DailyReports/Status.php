<?php

namespace App\Models\DailyReports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Status extends Model
{
    use SoftDeletes;

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

    public function userCanEdit($status = false) {
        if ($status) {
            $status = self::find($status);
        } else {
            $status = $this;
        }

        $user = Auth::user();

        if($user->isAdmin()) {
            return true;
        }

        if ($this->id == Status::where('slug', 'validation')->first()->id) {
            return true;
        } else if ($this->id == Status::where('slug', 'approval')->first()->id && $user->roles()->whereIn('slug', 'aprovacao')->first()) {
            return true;
        } else {
            return false;
        }
    }

    public function roles() {
        return $this->belongsToMany('App\Models\Role');
    }

    public function enabled() {
        return $this->enabled;
    }

    public function enable() {
        $this->enabled = true;
        $this->save();
    }

    public function disable() {
        $this->enabled = false;
        $this->save();
    }
}
