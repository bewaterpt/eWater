<?php

namespace App\Models\DailyReports;

use Illuminate\Database\Eloquent\Model;

class ReportLine extends Model
{

    public $timestamps = [
        'created_at',
        'updated_at',
        'entry_date',
    ];

    public function article() {
        return $this->belongsTo('App\Models\Article');
    }

    public function getTotal() {
        return $this->quantity * $this->unit_price;
    }

    public function report() {
        return $this->belongsTo('App\Models\DailyReports\Report');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function creator() {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function worker() {
        return $this->belongsTo('App\User', 'worker');
    }
}
