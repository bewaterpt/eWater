<?php

namespace App\Models\Connectors;

use Illuminate\Database\Eloquent\Model;

class OutonoFreguesias extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbFreguesias';

    protected $primaryKey = 'ff';

    public $timestamps = false;
}
