<?php

namespace App\Ldap\Rules;

use LdapRecord\Laravel\Auth\Rule;
use LdapRecord\Models\ActiveDirectory\Group;

class OutonoUsers extends Rule
{
    /**
     * Check if the rule passes validation.
     *
     * @return bool
     */
    public function isValid()
    {
        $outonoUsers = Group::find('cn=eWater_Users,ou=Applications,dc=bewater,dc=local');

        return $this->user->groups()->recursive()->exists($outonoUsers);
    }
}
