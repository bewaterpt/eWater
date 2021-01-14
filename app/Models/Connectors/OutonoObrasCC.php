<?php

namespace App\Models\Connectors;

use App\Models\Model;

class OutonoObrasCC extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbObrasCC';

    protected $primaryKey = 'numMov';

    public $timestamps = false;

    public static function lastInsertedEntryNumber() {
        return self::max('numLanc');
    }
}
