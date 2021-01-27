<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Excel;

class syncAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'addresses:update';

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
     * @return mixed
     */
    public function handle()
    {
        shell_exec('cd ' . base_path('scripts/postal_codes') . '; make scrape');
        $rows = Excel::toCollection([], storage_path('app') . '/temp/codigos_postais.csv');
        foreach ($rows as $key => $row) {
            if ($key = 0) {
            }

            dd($row);
        }
    }
}
