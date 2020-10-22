<?php

namespace App\Listeners;

use Lab404\Impersonate\Impersonate;
use Lab404\Impersonate\Events\TakeImpersonation;
use Log;
use Auth;
use App\User;

class LogTakeImpersonation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Lab404\Impersonate\Events\TakeImpersonation  $event
     * @return void
     */
    public function handle(TakeImpersonation $event)
    {
        Log::info(sprintf('User %s(id %d) started impersonating user %s(id %d)', $event->impersonator->name, $event->impersonator->id, $event->impersonated->name, $event->impersonated->id));
    }
}
