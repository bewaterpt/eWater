<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;
use App\Models\DailyReport\Article;

class ReportLine extends Model
{

    public function getArticle() {
        return Article::find($this->article_id);
    }

    public function getTotal() {
        return $this->quantity * $this->unit_price;
    }
}
