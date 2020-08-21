<?php

namespace App\Models\Connectors;

use Illuminate\Database\Eloquent\Model;

class OutonoDistritos extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbDistritos';

    protected $primaryKey = 'DD';

    public $timestamps = false;
}
