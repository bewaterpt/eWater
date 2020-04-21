<?php
namespace App\Helpers;

class LdapHelper {

    public static function getUserAccountControlAttributes($inputCode) {
        /**
        * http://support.microsoft.com/kb/305144
        *
        * You cannot set some of the values on a user or computer object because
        * these values can be set or reset only by the directory service.
        *
        */
        $userAccountControlFlags = array(
            16777216 => "TRUSTED_TO_AUTH_FOR_DELEGATION",
            8388608 => "PASSWORD_EXPIRED",
            4194304 => "DONT_REQ_PREAUTH",
            2097152 => "USE_DES_KEY_ONLY",
            1048576 => "NOT_DELEGATED",
            524288 => "TRUSTED_FOR_DELEGATION",
            262144 => "SMARTCARD_REQUIRED",
            131072 => "MNS_LOGON_ACCOUNT",
            65536 => "DONT_EXPIRE_PASSWORD",
            8192 => "SERVER_TRUST_ACCOUNT",
            4096 => "WORKSTATION_TRUST_ACCOUNT",
            2048 => "INTERDOMAIN_TRUST_ACCOUNT",
            512 => "NORMAL_ACCOUNT",
            256 => "TEMP_DUPLICATE_ACCOUNT",
            128 => "ENCRYPTED_TEXT_PWD_ALLOWED",
            64 => "PASSWD_CANT_CHANGE",
            32 => "PASSWD_NOTREQD",
            16 => "LOCKOUT",
            8 => "HOMEDIR_REQUIRED",
            2 => "ACCOUNTDISABLE",
            1 => "SCRIPT"
        );

        $attributes = NULL;
        while($inputCode > 0) {
            foreach($userAccountControlFlags as $flag => $flagName) {
                $temp = $inputCode-$flag;
                if($temp > 0) {
                    $attributes[$userAccountControlFlags[$flag]] = $flag;
                    $inputCode = $temp;
                }
                if($temp == 0) {
                    if(isset($userAccountControlFlags[$inputCode])) {
                        $attributes[$userAccountControlFlags[$inputCode]] = $inputCode;
                    }
                    $inputCode = $temp;
                }
            }
        }
        return $attributes;
    }

    public static function getErrorCode($diagnosticMessage) {
        return explode(' ', explode(', ', $diagnosticMessage)[2])[1];
    }
}
