<?php

namespace App\Models;

use App\Models\Model;

class Delegation extends Model
{
    public function users() {
        return $this->belongsToMany("App\User");
    }
}
