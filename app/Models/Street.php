<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    public function municipality() {
        return $this->belongsTo('App\Models\Municipality');
    }

    public function district() {
        return $this->belongsTo('App\Models\District');
    }

}
