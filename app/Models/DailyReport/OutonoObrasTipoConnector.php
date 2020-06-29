<?php

namespace App\Models\DailyReport;

use Illuminate\Database\Eloquent\Model;

class OutonoObrasTipoConnector extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbObrasTipo';

    protected $primaryKey = 'cod';

    public $timestamps = false;

    public function getById($workTypeId) {
        return self::find($workTypeId);
    }
}
