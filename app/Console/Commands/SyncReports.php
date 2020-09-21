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
    protected $signature = 'reports:sync {report?}';

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
        if ($this->argument('report')) {
            Log::info(sprintf('Syncronizing report #%d.', $this->argument('report')));
            $reports = Report::where('id', $this->argument('report'))->where('synced', false)->get();
        } else {
            Log::info('Syncronizing all pending reports.');
            $reports = Report::where('synced', false)->get();
        }

        if (!$reports) {
            return;
        }

        $transportArticle = Article::getTransportationArticle();
        $entryNumber = ObrasCC::lastInsertedEntryNumber() + 1;
        $reportLines;
        $reportTransportationData;
        $transportEntries = collect([]);
        $entries = collect([]);

        DB::beginTransaction();

        try {
            foreach ($reports as $report) {
                if ($report->getCurrentStatus()->first()->slug === 'database_sync') {
                    foreach ($report->lines()->get() as $line) {
                        $reportLines[$line->work_number][] = $line;

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
                        $transportEntry = new obrasCC();
                        $transportEntry->numLanc = $entryNumber;
                        $transportEntry->dtMov = Carbon::now()->format('Y-m-d h:i:s');
                        $transportEntry->clMov = $transportArticle->id;
                        $transportEntry->tpMov = 'D';
                        $transportEntry->numObra = $work_number;
                        $transportEntry->dtDoc = $reportTransportationData[$work_number]['date'];
                        $transportEntry->quantidade = $reportTransportationData[$work_number]['km'];
                        $transportEntry->precoUnitario = $transportArticle->unit_price;
                        $transportEntry->funcMov = $reportTransportationData[$work_number]['report']->creator()->first()->username;
                        $transportEntry->anulado = false;
                        $transportEntry->save();
                        $transportEntries->push($transportEntry);
                        Log::info(sprintf('Inserted transportation entry for work #%d with the following data: %s.', $work_number, json_encode($transportEntry)));

                        $entryNumber++;

                        foreach ($lines as $line) {
                            Log::info(sprintf('Inserting line for work #%d with article %s.', $work_number, $line->article()->first()->designation));

                            // $entry = new obrasCC();
                            $entry = [];
                            $article = $line->article()->first();
                            $entry['numLanc'] = $entryNumber;
                            $entry['dtMov'] = Carbon::now()->format('Y-m-d h:i:s');
                            $entry['clMov'] = $article->id;
                            $entry['tpMov'] = 'D';
                            $entry['numObra'] = $work_number;
                            $entry['dtDoc'] = $line->entry_date;
                            $entry['quantidade'] = $line->quantity;
                            $entry['precoUnitario'] = $article->unit_price;
                            $entry['funcMov'] = $line->user()->first()->username;
                            $entry['anulado'] = false;
                            // $entry->save();
                            $entryNumber++;
                            $entries->push($entry);
                            Log::info(sprintf('Inserted line for work #%d with the following data: %s.', $work_number, json_encode($entry)));
                        }

                        ObrasCC::insert($entries->toArray());
                    }

                    if (!$this->arguments('report')) {
                        $report->latestUpdate()->stepForward();
                    }
                    // $report->synced = true;
                    $report->save();
                }
            }

            Log::info(sprintf('Inserted entries with the following data: %s.', json_encode($entries)));
            Log::info(sprintf('Inserted transportation entries with the following data: %s.', json_encode($transportEntries)));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error(sprintf('Error occured while synchronizing report(s): %s.', json_encode($e->getMessage())));
            throw new \Exception($e->getMessage());
            $this->error("Error ". $e->getMessage());
        }
    }
}
