<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const TRANsPORTATION_ARTICLE_ID = 2;

    protected $connection = 'outono';

    protected $table = 'tbArtigos';

    protected $primaryKey = 'cod';

    public static function getArticleById($id) {
        return self::where('cod', $id)->first();
    }

    public static function getDailyReportRelevantArticles() {
        return self::whereIn('cod', [1, 3, 4]);
    }

    public static function getTransportationArticle() {
        return self::find(self::TRANSPORTATION_ARTICLE_ID);
    }
}
