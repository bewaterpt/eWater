<?php

namespace App\Models\Connectors;

use App\Models\Model;

class OutonoEstadosExecucao extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbEstadosExecucao';

    protected $primaryKey = 'cod';

    public $timestamps = false;
}
