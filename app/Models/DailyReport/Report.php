<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;
use App\Models\DailyReport\ProgressStatus;

class Report extends Model
{
    public function creator() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function processStatus() {
        return $this->hasMany('App\Models\DailyReport\ProcessStatus', 'process_id');
    }

    public function getCurrentStatus() {
        return $this->ProcessStatus()->latest()->first()->status();
    }

    public function cancel() {
        $processStatus = $this->processStatus()->latest()->first();
        $processStatus->cancel();
    }

    public function closed() {

    }
}
