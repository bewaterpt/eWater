<?php

namespace App\Mail;

use App\Helpers\Helper;
use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Interruption;
use Illuminate\Support\Carbon;

class InterruptionUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $prevInt;
    public $newInt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Interruption $prevInt, Interruption $newInt)
    {
        $this->prevInt = $prevInt;
        $this->newInt = $newInt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $translationString = $this->newInt->scheduled ? __('mail.interruptions.scheduled.created') : __('mail.interruptions.unscheduled.created');
        $scheduled = $this->newInt->scheduled ? 'scheduled' : 'unscheduled';
        $normalizedId = $this->prevInt->work_id != $this->newInt->work_id ? $this->prevInt->work_id . ' => ' . $this->newInt->work_id : $this->newInt->work_id;

        return $this->subject(__('mail.interruptions.' . $scheduled . '.updated_subject', ['id' => $normalizedId]))->view('mail.interruptions.updated', ['prevInt' => $this->prevInt, 'newInt' => $this->newInt, 'carbon' => new Carbon, 'helpers' => new Helper, 'delegation' => $this->newInt->delegation()->first(), 'translationString' => $translationString]);
    }
}
