<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use Auth;

use App\Models\Permission;

class Allowed
{
    /**
     * Depending on user's role and corresponding permissions, checks if user is allowed to access a route
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();



        if(!$user) {
            return redirect('/')->with('error', trans('auth.no_login'));
        }

        if (!$user->enabled) {
            Auth::logout();
            return redirect('login')->with('error', trans('auth.account_disabled'));
        }
        // dd($user);
        $current_route = Route::getCurrentRoute()->getName();
        $permission_model = new Permission();

        if (!$permission_model->can($current_route)) {
            return redirect()->back()->with('error', trans('auth.permission_denied'));
        }

        // dd($next($request));

        return $next($request);
    }
}
