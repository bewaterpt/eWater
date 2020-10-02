<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyReports\Report;
use App\Models\Connectors\OutonoObrasCC as ObrasCC;
use App\Models\Article;
use Illuminate\Support\Carbon;
use DB;
USE Log;

class SyncReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:sync {reports?} {userid?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza relatórios diários com a base de dados do Outono';

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
        $reports = null;
        if ($this->argument('reports') && $this->argument('reports') != 0) {
            $reportIds = explode(',', $this->argument('reports'));
            Log::info(sprintf('Syncronizing report(s) with id(s) [%s].', $this->argument('reports')));
            $reports = Report::whereIn('id', $reportIds)->where('synced', false)->get();
        } else {
            Log::info('Syncronizing all pending reports.');
            $reports = Report::where('synced', false)->get();
        }

        if (!$reports) {
            return;
        }

        $transportArticle = Article::getTransportationArticle();
        $entryNumber = ObrasCC::lastInsertedEntryNumber() + 1;

        $transportEntries = collect([]);
        $entries = collect([]);

        DB::beginTransaction();
        DB::connection('outono')->beginTransaction();

        try {
            foreach ($reports as $report) {

                $userId = null;
                if ($this->argument('userid')) {
                    $userId = $this->argument('userid');
                } else {
                    $userId = $report->user_id;
                }

                $this->info('Synchronizing report #' . $report->id);

                if ($report->getCurrentStatus()->first()->slug === 'database_sync') {
                    $reportLines = [];
                    $reportTransportationData = [];
                    foreach ($report->lines()->get() as $line) {
                        if ($i = 0) {
                            $reportLines[$line->work_number] = [];
                            $i ++;
                        }
                        $reportLines[$line->work_number][] = $line;
                        $reportTransportationData[$line->work_number] = [];

                        if (!isset($reportTransportationData[$line->work_number]['km'])) {
                            $reportTransportationData[$line->work_number]['km'] = $line->driven_km;
                        }

                        if (!isset($reportTransportationData[$line->work_number]['date'])) {
                            $reportTransportationData[$line->work_number]['date'] = $line->entry_date;
                        }

                        if (!isset($reportTransportationData[$line->work_number]['report'])) {
                            $reportTransportationData[$line->work_number]['report'] = $line->report()->first();
                        }
                    }

                    $reportLines = collect($reportLines);
                    $reportTransportationData = collect($reportTransportationData);
                    DB::enableQueryLog();

                    foreach ($reportLines as $work_number => $lines) {
                        Log::info(sprintf('Inserting transportation entry for work #%d.', $work_number));
                        $transportEntry = [];
                        $transportEntry['numLanc'] = $entryNumber;
                        $transportEntry['dtMov'] = Carbon::now()->format('Y-m-d h:i:s');
                        $transportEntry['clMov'] = $transportArticle->id;
                        $transportEntry['tpMov'] = 'D';
                        $transportEntry['numObra'] = $work_number;
                        $transportEntry['dtDoc'] = $reportTransportationData[$work_number]['date'];
                        $transportEntry['quantidade'] = (string) $reportTransportationData[$work_number]['km'];
                        $transportEntry['precoUnitario'] = $transportArticle->unit_price;
                        $transportEntry['funcMov'] = $reportTransportationData[$work_number]['report']->creator()->first()->username;
                        $transportEntry['anulado'] = false;

                        $entries->push($transportEntry);
                        Log::info(sprintf('Inserted transportation entry for work #%d with the following data: %s.', $work_number, json_encode($transportEntry)));


                        foreach ($lines as $line) {
                            Log::info(sprintf('Inserting line for work #%d with article %s.', $work_number, $line->article()->first()->designation));

                            $entry = [];
                            $article = $line->article()->first();
                            $line->entry_number = $entryNumber;
                            $line->save();
                            $entry['numLanc'] = $entryNumber;
                            $entry['dtMov'] = Carbon::now()->format('Y-m-d h:i:s');
                            $entry['clMov'] = $article->id;
                            $entry['tpMov'] = 'D';
                            $entry['numObra'] = $work_number;
                            $entry['dtDoc'] = $line->entry_date;
                            $entry['quantidade'] = (string) $line->quantity;
                            $entry['precoUnitario'] = $article->unit_price;
                            $entry['funcMov'] = $line->user()->first()->username;
                            $entry['anulado'] = false;

                            $entries->push($entry);
                            Log::info(sprintf('Inserted line for work #%d with the following data: %s.', $work_number, json_encode($entry)));
                        }

                    }

                    if (!$this->argument('reports')) {
                        $report->latestUpdate()->stepForward(false, $userId);
                    }

                    $report->synced = true;
                    $report->save();
                }

                $i = 0;
                $entryNumber++;
            }
            print_r($entries->chunk(200)->map(function ($chunk) {
                return ObrasCC::insert($chunk->toArray());
            }));
            // $entries = $error;

            Log::info(sprintf('Inserted entries with the following data: %s.', json_encode($entries)));
            DB::connection('outono')->commit();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            DB::connection('outono')->rollBack();

            Log::error(sprintf('Error occured while synchronizing report(s): %s.', $e->getMessage() . ' ocurred at line ' . $e->getLine()));
            $this->error("Error ". $e->getMessage() . ' at line ' . $e->getLine());
            throw new \Exception($e->getMessage() . ' at line ' . $e->getLine());
        }
    }
}
