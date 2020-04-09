<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use Auth;

use App\Models\role;
use App\Models\Permission;
use App\Models\EntUser;

class Allowed extends Middleware
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

        if (intval($user->enabled) === 0) {
            Auth::logout();
            return redirect('login')->with('error', trans('auth.permission_denied'));
        }

        $current_route = Route::getCurrentRoute()->getName();
        $permission_model = new Permission();

        if (!$permission_model->can($current_route)) {
            return redirect('/');
        }

        return $next($request);
    }
}
