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
        return self::where('id', $id)->exists();
    }
}
