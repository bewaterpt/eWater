<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{

    use SoftDeletes;

    public function failureType() {
        return $this->belongsTo('App\Models\FailureType');
    }
}
