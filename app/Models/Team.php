<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    public function users() {
        return $this->hasMany('App\User');
    }

    public function reports() {
        return $this->hasMany('App\Models\DailyReports\Report');
    }
}
