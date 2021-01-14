<?php

namespace App\Models\Connectors;

use App\Models\Model;

class OutonoConcelhos extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbConcelhos';

    protected $primaryKey = 'CC';

    public $timestamps = false;
}
