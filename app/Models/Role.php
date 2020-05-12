<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{

    use SoftDeletes;

    public function users() {
        return $this->belongsToMany('App\User');
    }

    public function permissions() {
        return $this->belongsToMany('App\Models\Permission');
    }

    public function countUsers() {
        return $this->users()->count();
    }
}
