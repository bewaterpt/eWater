<?php

namespace App\Listeners;

use App\Events\ReportStatusUpdated;
use Cache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateReportStatusValues
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
     * @param  ReportStatusUpdated  $event
     * @return void
     */
    public function handle(ReportStatusUpdated $event)
    {
        Cache::forget('datatable_reports');
        $event->report->current_status = $event->report->getCurrentStatus()->first()->name;
        $event->report->save();
    }
}
