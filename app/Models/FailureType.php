<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FailureType extends Model
{

    use SoftDeletes;

    public function materials() {
        return $this->hasMany('App\Models\Material');
    }

    public static function exists($id) {
        return FailureType::find($id)->count() > 0 ? true : false;
    }
}
