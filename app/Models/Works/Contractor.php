<?php

namespace App\Models\Works;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    public function works() {
        return $this->belongsToMany('App\Models\Work');
    }
}
