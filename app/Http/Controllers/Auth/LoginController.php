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
use Auth;
use App\Helpers\LdapHelper;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;
use App\Notifications\ApprovalPending;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
        parent::__construct();
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

    protected function handleLdapBindError($message, $code = null)
    {

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

            default:
                if ($message == "Can't contact LDAP server") {

                    // $user = User::where($this->username(), $this->userName)->first();

                    // if ($user) {
                    //     // dd(Auth::guard('db'));
                    //     Auth::guard('db')->login($user, true);
                    //     return redirect('/');
                    // }
                }
            break;
        }

        $this->baseHandleLdapBindError($message, $code);
    }

    protected function authenticated(Request $request, $user) {

        $user->notify(new ApprovalPending($user));

        Log::info('User {$user->name}({$user->username}) logged in at ' . Carbon::now());

        // $adminCount = DB::table('role_user')->where('role_id', 1)->count();
        if ($user->roles()->count() === 0) {
            if (in_array($user->username, ['admbm', 'admjc'])) {
                $user->roles()->syncWithoutDetaching([1]);
            } else {
                $user->roles()->syncWithoutDetaching([3]);
            }
        }
    }
}
