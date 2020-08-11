<?php

namespace App\Models\Connectors;

use Illuminate\Database\Eloquent\Model;

class OutonoInterrupcoesProg extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbInterrupcoesProg';

    protected $primaryKey = 'IdInterrupcoesProg';

    public $timestamps = false;
}
