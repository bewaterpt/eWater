<?php

namespace App\Models;

use App\Models\Model;

class Team extends Model
{
    public function users() {
        return $this->belongsToMany('App\User');
    }

    public function reports() {
        return $this->hasMany('App\Models\DailyReports\Report');
    }
}
