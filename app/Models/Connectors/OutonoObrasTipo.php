<?php

namespace App\Models\Connectors;

use App\Models\Model;

class OutonoObrasTipo extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbObrasTipo';

    protected $primaryKey = 'cod';

    public $timestamps = false;

    public function getById($workTypeId) {
        return self::find($workTypeId);
    }
}
