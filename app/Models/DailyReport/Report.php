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

    public function latestUpdate() {
        return $this->hasMany('App\Models\DailyReport\ProcessStatus', 'process_id')->latest('id')->first();
    }

    public function lines() {
        return $this->hasMany('App\Models\DailyReport\ReportLine');
    }

    public function getCurrentStatus() {
        return $this->latestUpdate()->status();
    }

    public function cancel() {
        $processStatus = $this->processStatus()->latest()->first();
        return $processStatus->cancel();
    }

    public function closed() {
        $processStatus = $this->processStatus()->latest()->first();
        return $processStatus->closed();
    }

    public function getTotalPrice() {
        return $this->lines()->get()->map(function ($line) {
            return $line->quantity * $line->unit_price;
        })->sum();
    }
}
