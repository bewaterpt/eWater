<?php

namespace App\Models\Connectors;

use App\Models\Model;

class OutonoInterrupcoes extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbInterrupcoes';

    protected $primaryKey = 'IdInterrupcoes';

    public $timestamps = false;
}
