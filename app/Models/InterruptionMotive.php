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

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function updatedBy() {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public static function scheduled() {
        return self::where('scheduled', true)->get();
    }

    public static function unscheduled() {
        return self::where('scheduled', false)->get();
    }
}
