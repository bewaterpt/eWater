<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Helpers\Helper;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AddUserToRole::class,
        Commands\ClearCache::class,
        Commands\CreateNewRole::class,
        Commands\CustomMigrateFresh::class,
        Commands\DeleteAllArticles::class,
        Commands\GenerateData::class,
        Commands\GetCDRRecords::class,
        Commands\syncAddresses::class,
        Commands\SyncADGroups::class,
        Commands\SyncArticles::class,
        Commands\SyncPermissions::class,
        Commands\SyncReports::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('telescope:prune')->weekly();
        $schedule->command('calls:get')->dailyAt('07:00');
        $schedule->command('permissions:update')->dailyAt('08:00');
        $schedule->command('roles:update')->dailyAt('08:05');
        // $schedule->command('reports:sync')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
