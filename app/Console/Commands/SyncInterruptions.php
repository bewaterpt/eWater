<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Connectors\OutonoInterrupcoes;
use App\Models\Connectors\OutonoInterrupcoesProg;
use App\Models\Interruption;

class SyncInterruptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'interruptions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports interruptions from the Outono 2005 app';

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
        $unscheduledInterruptions = OutonoInterrupcoes::select('*');

        $scheduledInterruptions = OutonoInterrupcoesProg::select('*');
        $newInterruptions = collect([]);

        if (Interruption::where('scheduled', false)->first()) {
            $unscheduledInterruptions->where('IdInterrupcoes', '>', Interruption::where('scheduled', false)->latest('outono_id')->first()->outono_id);
        }

        if (Interruption::where('scheduled', true)->first()) {
            $scheduledInterruptions->where('IdInterrupcoesProg', '>', Interruption::where('scheduled', true)->latest('outono_id')->first()->outono_id);
        }

        if ($unscheduledInterruptions->count() > 0) {
            $this->info('Inserting ' . $unscheduledInterruptions->count() . ' unscheduled interruptions.');
            $bar = $this->output->createProgressBar($unscheduledInterruptions->count());
            $bar->start();

            $unscheduledInterruptions->chunk(200, function ($interruptions) use ($bar, $newInterruptions) {
                foreach ($interruptions as $interruption) {
                    $newInterruption = new Interruption();
                    $newInterruption->work_id = $interruption->numObra;
                    $newInterruption->start_date = Carbon::parse($interruption->dtInicio)->format('Y-m-d H:i:s');
                    $newInterruption->reinstatement_date = Carbon::parse($interruption->dtRestabelecimento)->format('Y-m-d H:i:s');;
                    $newInterruption->scheduled = false;
                    $newInterruption->delegation()->associate(1);
                    $newInterruption->user()->associate(1);
                    $newInterruption->created_at = Carbon::now();
                    $newInterruption->updated_at = Carbon::now();
                    $newInterruption->affected_area = $interruption->areaAfectada;
                    $newInterruption->coordinates = null;
                    $newInterruption->outono_id = $interruption->IdInterrupcoes;
                    $newInterruption->synced = true;
                    $newInterruption->save();
                    $newInterruptions->push($newInterruption);
                    $bar->advance();
                }
            });

            $bar->finish();
        } else {
            $this->comment('No unscheduled interruptions to synchronize');
        }

        if ($scheduledInterruptions->count() > 0){
            $this->info('');
            $this->info('Inserting ' . $scheduledInterruptions->count() . ' unscheduled interruptions.');
            $bar = $this->output->createProgressBar($scheduledInterruptions->count());
            $bar->start();

            $scheduledInterruptions->chunk(200, function ($interruptions) use ($bar, $newInterruptions) {

                foreach ($interruptions as $interruption) {
                    $newInterruption = new Interruption();
                    $newInterruption->work_id = $interruption->numObra;
                    $newInterruption->start_date = $interruption->dtInicio;
                    $newInterruption->reinstatement_date = $interruption->dtRestabelecimento;
                    $newInterruption->scheduled = true;
                    $newInterruption->delegation()->associate(1);
                    $newInterruption->user()->associate(1);
                    $newInterruption->created_at = Carbon::now();
                    $newInterruption->updated_at = Carbon::now();
                    $newInterruption->affected_area = $interruption->areaAfectada;
                    $newInterruption->coordinates = null;
                    $newInterruption->outono_id = $interruption->IdInterrupcoesProg;
                    $newInterruption->synced = true;
                    $newInterruption->save();
                    $newInterruptions->push($newInterruption);
                    $bar->advance();
                }
            });

            $bar->finish();
        } else {
            $this->info('');
            $this->comment('No scheduled interruptions to synchronize');
        }

        $this->info('');
        $this->info('Done');

        return 0;
    }
}
