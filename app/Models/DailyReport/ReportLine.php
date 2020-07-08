<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;
use App\Models\DailyReport\Article;

class ReportLine extends Model
{

    public $timestamps = [
        'created_at',
        'updated_at',
        'entry_date',
    ];

    public function getArticle() {
        return Article::find($this->article_id);
    }

    public function getTotal() {
        return $this->quantity * $this->unit_price;
    }

    public function report() {
        return $this->belongsTo('App\Models\DailyReport\Report');
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
