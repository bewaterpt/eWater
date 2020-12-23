<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{

    use SoftDeletes;

    public function failureType() {
        return $this->belongsTo('App\Models\FailureType');
    }
}
