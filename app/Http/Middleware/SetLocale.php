<?php

namespace App\Http\Middleware;

use Closure;
Use Auth;
use Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            app()->setLocale($user->locale);
        } else {
            $locale = $request->session()->get('locale', config('app.locale'));
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
