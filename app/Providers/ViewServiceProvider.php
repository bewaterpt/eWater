<?php
namespace App\Providers;

use Illuminate\Support\Facades\View;
use DB;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
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
        // Using Closure based composers...
        View::composer(['errors::*'], function ($view) {
            $uuid = DB::table('telescope_entries as entries')
                        ->join('telescope_entries_tags as tags', 'entries.uuid', '=', 'tags.entry_uuid')
                        ->select('entries.uuid')
                        ->where('entries.type', 'exception')
                        ->orderByDesc('created_at')
                        ->limit(1)
                        ->first()->uuid;
            $view->with('uuid', $uuid);
        });
    }
}
