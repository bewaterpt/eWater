<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailureType extends Model
{

    public function materials() {
        return $this->belongsToMany('App\Models\Materials');
    }
}
