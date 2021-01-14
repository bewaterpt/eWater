<?php

namespace App\Models\Works;

use App\Models\Model;

class Contractor extends Model
{
    public function works() {
        return $this->belongsToMany('App\Models\Work');
    }
}
