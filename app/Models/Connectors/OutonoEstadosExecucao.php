<?php

namespace App\Models\Connectors;

use Illuminate\Database\Eloquent\Model;

class OutonoEstadosExecucao extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbEstadosExecucao';

    protected $primaryKey = 'cod';

    public $timestamps = false;
}
