<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Interruption;
use Illuminate\Support\Carbon;

class InterruptionUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public Interruption $interruption;

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
        return $this->subject('Teste ' . __('mail.interruptions.updated'))->view('mail.new-interruption', ['interruption' => $this->interruption, 'carbon' => new Carbon]);
    }
}
