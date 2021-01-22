<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Mail;
use Auth;
use Illuminate\Http\Request;
use App\User;

class InterruptionMotive extends Model
{
    use HasFactory, SoftDeletes;

<<<<<<< HEAD
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function updatedBy() {
        return $this->belongsTo('App\User', 'updated_by');
    }

=======
>>>>>>> 874378e9d31499c18f3e065ac41e26d2b0bfed33
}
