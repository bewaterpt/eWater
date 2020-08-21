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

    public static function exists($id) {
        return self::where('numObra', $id)->whereNotIn('codEstadoExecucao', [3, 4])->exists();
    }

    public function getStreet() {
        return $this->belongsTo('App\Models\Connectors\OutonoRuas', 'codRua');
    }

    public function getType() {
        return $this->belongsTo('App\Models\Connectors\OutonoObrasTipo', 'codObra');
    }
}
