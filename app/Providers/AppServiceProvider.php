<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Auth;
use View;
use Session;
use App\Models\Permission;
use App\Extensions\NoIpSessionHandler;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $is_logged_in = false;
        // $user = Auth::user();
        // $user_roles = null;

        // if ($user) {
        //     $user_roles = $user->roles;
        // }


        // $permission = new Permission();

        Session::extend('noip', function ($app) {
            // Return implementation of SessionHandlerInterface...
            return new NoIpSessionHandler;
        });
    }
}
