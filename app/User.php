<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Tell Laravel to automaticaly define
     * created_at and updated_at fields
     */
    public $timestamps = true;

    /**
     * Return the relationship for the user roles.
     *
     * @param type $for_select2 - Is the output going  to a select2 element?
     *
     * @return type
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
}
