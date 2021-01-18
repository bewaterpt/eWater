<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyReports\Report;
use Carbon\Carbon;

class MigrateReportsToNewDBStructure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:migrate';

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
        $reports = Report::all();

        $bar = $this->output->createProgressBar($reports->count());
        $bar->start();

        foreach ($reports as $report) {
            $report->date = Carbon::parse($report->getEntryDate())->format('Y-m-d H:i:s');
            $report->current_status = $report->getCurrentStatus()->first()->name;
            $report->save();

            $bar->advance();
        }

        $bar->finish();
        return 0;
    }
}
