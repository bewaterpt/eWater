<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interruption extends Model
{

    use SoftDeletes;

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function delegation() {
        return $this->belongsTo('App\Models\Delegation');
    }

    public function updatedBy() {
        return $this->belongsTo('App\User', 'updated_by');
    }
}
