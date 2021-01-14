<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\InterruptionCreated;
use App\Models\Interruption;
use Mail;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test';

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
        Mail::to(config('app.emails.interruptions_ao'))->send(new InterruptionCreated($interruption));

        return 0;
    }
}
