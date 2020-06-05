<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;

class OutonoObrasCCConnector extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbObrasCC';

    public static function lastInsertedEntryNumber() {
        return self::max('numLanc');
    }
}
