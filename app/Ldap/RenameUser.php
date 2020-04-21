<?php

namespace App\Ldap;

use LdapRecord\Models\Model;

class LdapUser extends Model
{
    /**
     * The object classes of the LDAP model.
     *
     * @var array
     */
    public static $objectClasses = [
        'top',
        'person',
        'organizationalperson',
        'user',
    ];
}
