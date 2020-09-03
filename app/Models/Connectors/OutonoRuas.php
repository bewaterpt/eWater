<?php

namespace App\Models\Connectors;

use Illuminate\Database\Eloquent\Model;

class OutonoRuas extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbRuas';

    protected $primaryKey = 'ART_COD';

    public $timestamps = false;

    public function getLocality() {
        return $this->belongsTo('App\Models\Connectors\OutonoLocalidades', 'LLLL');
    }
}