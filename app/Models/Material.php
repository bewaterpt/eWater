<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{

    public function failureType() {
        return $this->hasOne('App\Models\FailureType');
    }
}
