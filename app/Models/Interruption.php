<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interruption extends Model
{
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function delegation() {
        return $this->belongsTo('App\Models\Delegation');
    }
}
