<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

class ClearTemp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:cleartemp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears temporary files';

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
        collect(Storage::disk('local')->files('temp'))->map(function ($item) {
            Storage::disk('local')->delete($item);
        });
    }
}
