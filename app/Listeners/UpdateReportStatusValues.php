<?php

namespace App\Listeners;

use App\Events\ReportStatusUpdated;
use Illuminate\Support\Carbon;
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
        if ($event->report->lines->count() > 0) {
            $event->report->date = Carbon::parse($event->report->getEntryDate())->format('Y-m-d H:i:s');
        }
        $event->report->current_status = $event->report->getCurrentStatus()->first()->name;
        $event->report->save();
    }
}
