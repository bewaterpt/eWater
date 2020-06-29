<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;

class OutonoObrasConnector extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbObras';

    protected $primaryKey = 'numObra';

    public $timestamps = false;

    public function getById($workId) {
        return self::find($workId);
    }
}
