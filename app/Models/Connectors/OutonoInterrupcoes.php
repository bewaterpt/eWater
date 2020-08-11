<?php

namespace App\Models\Connectors;

use Illuminate\Database\Eloquent\Model;

class OutonoInterrupcoes extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbInterrupcoes';

    protected $primaryKey = 'numObra';

    public $timestamps = false;
}
