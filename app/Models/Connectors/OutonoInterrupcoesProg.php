<?php

namespace App\Models\Connectors;

use App\Models\Model;

class OutonoInterrupcoesProg extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbInterrupcoesProg';

    protected $primaryKey = 'IdInterrupcoesProg';

    public $timestamps = false;
}
