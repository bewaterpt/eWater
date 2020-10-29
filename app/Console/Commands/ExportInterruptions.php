<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Interruption;
use App\Exports\InterruptionsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportInterruptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interruptions:export {amount?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Exports interruptions';

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
        Excel::store(new InterruptionsExport(10), 'teste.xls', 'interruptions');
    }
}
