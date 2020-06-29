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

    public function linesByWorkNumber($workNumber) {
        $lines = $this->hasMany('App\Models\DailyReport\ReportLine')->get();
        $processedLines = [];

        foreach ($lines as $line) {
            if ($line->work_number === $workNumber) {
                $processedLines[] = $line;
            }
        }

        return collect($processedLines);
    }

    public function getCurrentStatus() {
        return $this->latestUpdate()->status();
    }

    public function cancel() {
        $processStatus = $this->processStatus()->latest()->first();
        return $processStatus->cancel();
    }

    public function closed() {
        $processStatus = $this->latestUpdate();
        return $processStatus->closed();
    }

    /**
     * @method getTotalPrice()
     *
     * Gets the sum of all unit prices related to this report for the price total
     *
     * @deprecated because prices are not needed
     */
    public function getTotalPrice() {
        return $this->lines()->get()->map(function ($line) {
            return $line->quantity * $line->unit_price;
        })->sum();
    }

    public function getTotalHours() {
        return $this->lines()->get()->sum('quantity');
    }

    public function getTotalKm() {
        return $this->lines()->get()->sum('driven_km');
    }

    public static function notSynced() {
        return self::where('synced', false);
    }
}
