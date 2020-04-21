<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Adldap;
use App\User;
use DB;
use App\Helpers\LdapHelper;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, ListensForLdapBindFailure {
        handleLdapBindError as baseHandleLdapBindError;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Username
     *
     * @var string
     */
    protected $userName = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest')->except('logout');
        $this->userName = $request->username;
    }

    public function credentials(Request $request) {
        return [
            'samaccountname' => $request->username,
            'password' => $request->password,
        ];
    }

    public function username() {
        return 'username';
    }

    protected function handleLdapBindError($message, $code = null) {
        switch ($code) {
            case '532':
                return redirect('/')->withErrors(__('auth.password_expired', ['url' => config('app.reset_password_url'), 'username' => $this->userName]), 'custom');
                break;

            case '533':
                return redirect('/')->withErrors(__('auth.account_disabled', ['helpdesk_email' => config('app.helpdesk_email')]), 'custom');
                break;

            case '701':
                return redirect('/')->withErrors(__('auth.account_expired', ['helpdesk_email' => config('app.helpdesk_email')]), 'custom');
                break;

            case '773':
                return redirect('/')->withErrors(__('auth.password_expired', ['url' => config('app.reset_password_url'), 'username' => $this->userName]), 'custom');
                break;
        }

        $this->baseHandleLdapBindError($message, $code);
    }

    protected function authenticated(Request $request, $user) {

        $is_first_admin = DB::table('role_user')->where('role_id', 1)->count();

        if ($is_first_admin <= 1) {
            $user->roles()->attach(1);
        } else {
            $user->roles()->attach(2);
        }
    }

    // protected function attemptLogin(Request $request)
    // {
    //     $credentials = $request->only($this->username(), 'password');
    //     $username = $credentials[$this->username()];
    //     $password = $credentials['password'];

    //     $user_format = env('LDAP_USER_FORMAT', '%s@bewater.local');
    //     $userdn = sprintf('%s', $username);
    //     $is_first_admin = DB::table('role_user')->where('role_id', 1)->count();
    //     // you might need this, as reported in
    //     // [#14](https://github.com/jotaelesalinas/laravel-simple-ldap-auth/issues/14):
    //     // Adldap::auth()->bind('bewater\admbm', 'eTzTT5a85');
    //     $result = Adldap::auth()->attempt($userdn, $password, true);

    //     if ($result['state']) {
    //         // the user exists in the LDAP server, with the provided password

    //         $user = User::where($this->username(), $username)->first();
    //         if (!$user) {
    //             // the user doesn't exist in the local database, so we have to create one

    //             $user = new User();
    //             $user->username = $username;
    //             $user->password = '';
    //             // $user->updateTimestamps();

    //             // you can skip this if there are no extra attributes to read from the LDAP server
    //             // or you can move it below this if(!$user) block if you want to keep the user always
    //             // in sync with the LDAP server
    //             $sync_attrs = $this->retrieveSyncAttributes(explode('\\', $username)[1]);
    //             foreach ($sync_attrs as $field => $value) {
    //                 echo $field;
    //                 if ($field == "uac") {
    //                     dd(LdapHelper::getUserAccountControlAttributes($value));
    //                     continue;
    //                 }
    //                 $user->$field = $value !== null ? $value : '';
    //             }

    //             $user->save();
    //             if ($is_first_admin == 0 || $is_first_admin == 1) {
    //                 $user->roles()->attach(1);
    //             } else {
    //                 $user->roles()->attach(2);
    //             }

    //             // by logging the user we create the session, so there is no need to login again (in the configured time).
    //             // pass false as second parameter if you want to force the session to expire when the user closes the browser.
    //             // have a look at the section 'session lifetime' in `config/session.php` for more options.

    //         } else {
    //             $sync_attrs = $this->retrieveSyncAttributes(explode('\\', $username)[1]);
    //             $attributes = LdapHelper::getUserAccountControlAttributes($sync_attrs['uac']);

    //             foreach ($attributes as $attribute => $value) {
    //                 if ($attribute === "") {

    //                 }
    //             }
    //         }

    //         if (!$user->enabled) {
    //             return redirect('/')->withErrors([trans('auth.account_disabled', ['helpdesk_email' => config('app.helpdesk_email')])]);
    //         }

    //         $this->guard()->login($user, true);
    //         return true;
    //     } else {

    //         switch (LdapHelper::getErrorCode($result['message']->getDetailedError()->getDiagnosticMessage())) {
    //             case '532':
    //                 return redirect('/')->withErrors([trans('auth.password_expired', ['url' => config('reset_password_url'), 'username' => $username])]);
    //                 break;

    //             case '533':
    //                 return redirect('/')->withErrors([trans('auth.account_disabled', ['helpdesk_email' => config('app.helpdesk_email')])]);
    //                 break;

    //             case '701':
    //                 return redirect('/')->withErrors([trans('auth.account_expired', ['helpdesk_email' => config('app.helpdesk_email')])]);
    //                 break;

    //             case '773':
    //                 return redirect('/')->withErrors([trans('auth.password_expired', ['url' => config('reset_password_url'), 'username' => $username])]);
    //                 break;
    //         }

    //         $user = User::where($this->username(), $username)->first();
    //         if(!$user) {
    //             return redirect('/')->with([
    //                 'errors' => trans('auth.failed', ['url' => config('reset_password_url'), 'username' => $username]),
    //             ]);
    //         }
    //         $this->guard()->login($user, true);
    //         return true;
    //     }

    //     // the user doesn't exist in the LDAP server or the password is wrong
    //     // log error
    //     return false;
    // }

    protected function retrieveSyncAttributes($username)
    {
        $ldapuser = Adldap::search()->where(env('LDAP_USER_ATTRIBUTE'), '=', $username)->first();

        // echo json_encode($ldapuser->givenName);
        // die;
        if ( !$ldapuser ) {
            // log error
            return false;
        }
        // if you want to see the list of available attributes in your specific LDAP server:
        // var_dump($ldapuser->attributes); exit;

        // needed if any attribute is not directly accessible via a method call.
        // attributes in \Adldap\Models\User are protected, so we will need
        // to retrieve them using reflection.
        $ldapuser_attrs = null;

        $attrs = [];

        foreach (config('ldap_auth.sync_attributes') as $local_attr => $ldap_attr) {
            if ( $local_attr == 'username' ) {
                continue;
            }

            $method = 'get' . $ldap_attr;
            if (method_exists($ldapuser, $method)) {
                $attrs[$local_attr] = $ldapuser->$method();
                continue;
            }

            if ($ldapuser_attrs === null) {
                $ldapuser_attrs = self::accessProtected($ldapuser, 'attributes');
            }

            if (!isset($ldapuser_attrs[$ldap_attr])) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }

            if (!is_array($ldapuser_attrs[$ldap_attr])) {
                $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr];
            }



            if (count($ldapuser_attrs[$ldap_attr]) == 0) {
                // an exception could be thrown
                $attrs[$local_attr] = null;
                continue;
            }


            // now it returns the first item, but it could return
            // a comma-separated string or any other thing that suits you better
            $attrs[$local_attr] = $ldapuser_attrs[$ldap_attr][0];
            //$attrs[$local_attr] = implode(',', $ldapuser_attrs[$ldap_attr]);

        }

        return $attrs;
    }

    protected static function accessProtected ($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }
}
