<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;

class ProcessStatus extends Model
{
    public function report() {
        return $this->belongsTo('App\Models\DailyReport\Report');
    }

    public function status() {
        return $this->belongsTo('App\Models\DailyReport\Status');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
