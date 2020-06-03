<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $connection = 'outono';

    protected $table = 'tbArtigos';

    public static function getArticleById($id) {
        return self::where('cod', $id)->first();
    }
}