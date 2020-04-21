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
            return redirect('/')->withErrors(__('auth.no_login'), 'custom');
        }

        if (!$user->enabled) {
            Auth::logout();
            return redirect('login')->withErrors(__('auth.account_disabled'), 'custom');
        }

        $current_route = Route::getCurrentRoute()->getName();
        $permission_model = new Permission();

        if (!$permission_model->can($current_route)) {
            return redirect()->back()->withErrors(__('auth.permission_denied', ['route' => $current_route]), 'custom');
        }

        // dd($next($request));

        return $next($request);
    }
}
