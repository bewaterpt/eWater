<?php

namespace App\Models;

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
