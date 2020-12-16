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

    public Interruption $prevInt;
    public Interruption $newInt;

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
        return $this->subject('Teste ' . __('mail.interruptions.updated'))->view('mail.interruptions.updated', ['prevInt' => $this->prevInt, 'newInt' => $this->newInt, 'carbon' => new Carbon, 'helpers' => new Helper, 'delegation' => $this->newInt->delegation()->first()]);
    }
}
