<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public function creator() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function status() {
        $relation = $this->hasMany('App\Models\DailyReport\ProcessStatus', 'process_id');

        dd($relation);
    }
}
