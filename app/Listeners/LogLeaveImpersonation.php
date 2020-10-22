<?php

namespace App\Listeners;

use Lab404\Impersonate\Impersonate;
use Lab404\Impersonate\Events\LeaveImpersonation;
use Log;
use Auth;
use App\User;

class LogLeaveImpersonation
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
     * @param \Lab404\Impersonate\Events\LeaveImpersonation  $events
     * @return void
     */
    public function handle(LeaveImpersonation $event)
    {
        Log::info(sprintf('User %s(id %d) stopped impersonating user %s(id %d)', $event->impersonator->name, $event->impersonator->id, $event->impersonated->name, $event->impersonated->id));
    }
}
