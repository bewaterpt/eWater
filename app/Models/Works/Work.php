<?php

namespace App\Models\Works;

use Illuminate\Database\Eloquent\Model;

class Work extends Model
{
    public function user() {
        return $this->hasOne('App\User');
    }

    public function contractor() {
        return $this->hasOne('App\Models\Works\Contractor');
    }
}
