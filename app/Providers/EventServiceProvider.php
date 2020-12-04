<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Lab404\Impersonate\Events\TakeImpersonation;
use Lab404\Impersonate\Events\LeaveImpersonation;
use App\Listeners\LogTakeImpersonation;
use App\Listeners\LogLeaveImpersonation;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TakeImperssonation::class => [
            LogTakeImpersonation::class,
        ],
        LeaveImperssonation::class => [
            LogLeaveImpersonation::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
