<?php

namespace App\Models\Connectors;

use App\Models\Model;

class OutonoFreguesias extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbFreguesias';

    protected $primaryKey = 'ff';

    public $timestamps = false;
}
