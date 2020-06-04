<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public function getCurrentStatus() {
        return $this->hasOne('App\Models\DailyReport\Status', 'current_status');
    }

    public function getCreator() {
        return $this->hasOne('App\User');
    }
}
