<?php

namespace App\Models\Connectors;

use App\Models\Model;

class OutonoLocalidades extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbLocalidades';

    protected $primaryKey = 'LLLL';

    public $timestamps = false;

    public function getDistrict() {
        return $this->belongsTo('App\Models\Connectors\OutonoDistritos', 'DD');
    }

    public function getProvince() {
        return $this->belongsTo('App\Models\Connectors\OutonoConcelhos', 'CC');
    }

    public function getTown() {
        return $this->belongsTo('App\Models\Connectors\OutonoFreguesias', 'FF');
    }
}
