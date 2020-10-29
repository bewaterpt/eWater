<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use LdapRecord\Laravel\Auth\HasLdapUser;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements LdapAuthenticatable
{
    use Notifiable, AuthenticatesWithLdap, HasLdapUser, SoftDeletes, HasApiTokens, Impersonate;

    protected $primaryKey = 'id';

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
     * @return relationship
     */
    public function roles() {
        return $this->belongsToMany('App\Models\Role');
    }

    /**
     * Return the relationship for the user agent
     *
     * @return relationship
     */
    public function agent() {
        return $this->belongsTo('App\Models\Agent');
    }

    /**
     * Check if the user is an agent
     *
     * @return boolean
     */
    public function isAgent() {
        if ($this->belongsTo('App\Models\Agent')->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Check if the user is enabled
     *
     * @return boolean
     */
    public function enabled() {
        return $this->enabled;
    }

    /**
     * Enable the user and the associated agent if there is one
     *
     * @param boolean $enableAgent
     *
     * @
     */
    public function enable($enableAgent = false) {
        $this->enabled = true;
        if($this->isAgent()) {
            $this->agent()->enable();
        }
        $this->save();
    }

    /**
     * Disable the user and the associated agent if there is one
     */
    public function disable() {
        $this->enabled = false;
        if($this->isAgent()) {
            $this->agent()->disable();
        }
        $this->save();
    }

    public function delegation() {
        return $this->belongsTo('App\Models\Delegation');
    }

    public function reports() {
        return $this->hasMany('App\Models\DailyReports\Report');
    }

    public function teams() {
        return $this->belongsToMany('App\Models\Team');
    }

    public function isAdmin() {
        return $this->roles()->where('id', 1)->first() ? true : false ;
    }

    public function canImpersonate() {
        return $this->isAdmin();
    }
}
