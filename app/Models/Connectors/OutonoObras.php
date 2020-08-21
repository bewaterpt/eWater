<?php

namespace App\Models\Connectors;

use Illuminate\Database\Eloquent\Model;

class OutonoObras extends Model
{
    protected $connection = 'outono';

    protected $table = 'tbObras';

    protected $primaryKey = 'numObra';

    public $timestamps = false;

    public function getById($workId) {
        return self::find($workId);
    }

    public function active() {
        return in_array($this->codEstadoExecucao, [3, 4]) ? false : true;
    }

    public function getStreet() {
        return $this->belongsTo('App\Models\Connectors\OutonoRuas', 'codRua');
    }

    public function getType() {
        return $this->belongsTo('App\Models\Connectors\OutonoObrasTipo', 'codObra');
    }

    public function executionStateText() {
        return strtolower($this->belongsTo('App\Models\Connectors\OutonoEstadosExecucao', 'codEstadoExecucao')->first()->descricao);
    }
}
