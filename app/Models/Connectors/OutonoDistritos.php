<?php

namespace App\Models\Connectors;

use App\Models\Model;

class OutonoDistritos extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbDistritos';

    protected $primaryKey = 'DD';

    public $timestamps = false;
}
