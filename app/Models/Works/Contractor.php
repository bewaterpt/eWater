<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contractor extends Model
{
    public function works() {
        return $this->belongsToMany('App\Models\Work');
    }
}
