<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\InterruptionCreated;
use App\Mail\InterruptionUpdated;
use App\Mail\InterruptionCanceled;
use App\Models\Interruption;
use Mail;

class TestInterruptionEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interruptions:testmail {mail?} {interruptionType?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $interruption = Interruption::find(100);
        $interruptionForComparison = Interruption::find(300);

        $arg = $this->argument('interruptionType');
        $mailable = ($arg == 1 ? new InterruptionCreated($interruption) : ($arg == 2 ? new InterruptionUpdated($interruption, $interruptionForComparison) : ($arg == 3 ? new InterruptionCanceled($interruption) : new InterruptionCreated($interruption))));

        Mail::to($this->argument('mail') ? $this->argument('mail') : config('app.emails.interruptions_ao'))->send($mailable);

        return 0;
    }
}
