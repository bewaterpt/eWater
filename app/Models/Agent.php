<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{

    use SoftDeletes;

    public function user() {
        return $this->hasOne('App\User');
    }

    public function enabled() {
        return $this->enabled;
    }

    public function enable() {
        $this->enabled = true;
        $this->save();
    }

    public function disable() {
        $this->enabled = false;
        $this->save();
    }
}
