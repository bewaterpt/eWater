<?php

namespace App\Models\DailyReports;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const TRANSPORTATION_ARTICLE_ID = 2;

    protected $connection = 'outono';

    protected $table = 'tbArtigos';

    protected $primaryKey = 'cod';

    public static function getById($articleId) {
        return self::find($articleId);
    }

    public static function getDailyReportRelevantArticles() {
        return self::whereIn('cod', [1, 3, 4]);
    }

    public static function getTransportationArticle() {
        return self::find(self::TRANSPORTATION_ARTICLE_ID);
    }
}
