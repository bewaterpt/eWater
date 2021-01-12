<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Interruption;
use Illuminate\Support\Carbon;

class InterruptionCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $interruption;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Interruption $interruption)
    {
        $this->interruption = $interruption;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $scheduled = $this->interruption->scheduled ? 'scheduled' : 'unscheduled';

        return $this->subject(__('mail.interruptions.' . $scheduled . '.created'))->view('mail.interruptions.created', ['interruption' => $this->interruption, 'carbon' => new Carbon, 'delegation' => $this->interruption->delegation()->first(), 'scheduled' => $scheduled]);
    }
}
