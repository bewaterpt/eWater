<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyReports\Report;
use App\Models\Connectors\OutonoArtigos as Artigos;
use App\Models\Connectors\OutonoObrasCC as ObrasCC;
use App\Models\Article;
use Illuminate\Support\Carbon;
use DB;

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
        $report = null;
        if ($this->argument('report')) {
            $reports = Report::where('id', $this->argument('report'))->where('synced', false)->get();
        } else {
            $reports = Report::where('synced', false)->get();
        }

        if (!$reports) {
            return;
        }

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

                    foreach ($reportLines as $work_number => $lines) {
                        $transportEntry = new obrasCC();
                        $transportArticle = Article::getTransportationArticle();
                        $transportEntry->numLanc = $transportEntry->lastInsertedEntryNumber()+1;
                        $transportEntry->dtMov = Carbon::now()->format('Y-m-d h:i:s');
                        $transportEntry->clMov = $transportArticle->id;
                        $transportEntry->tpMov = 'D';
                        $transportEntry->numObra = $work_number;
                        $transportEntry->dtDoc = $reportTransportationData[$work_number]['date'];
                        $transportEntry->quantidade = $reportTransportationData[$work_number]['km'];
                        $transportEntry->precoUnitario = $transportArticle->unit_price;
                        $transportEntry->funcMov = $reportTransportationData[$work_number]['report']->creator()->first()->username;
                        $transportEntry->anulado = false;

                        $transportEntries->push($transportEntry);
                        $transportEntry->save();

                        foreach ($lines as $line) {
                            $entry = new obrasCC();
                            $article = $line->article()->first();
                            $entry->numLanc = $entry->lastInsertedEntryNumber()+1;
                            $entry->dtMov = Carbon::now()->format('Y-m-d h:i:s');
                            $entry->clMov = $article->id;
                            $entry->tpMov = 'D';
                            $entry->numObra = $work_number;
                            $entry->dtDoc = $line->entry_date;
                            $entry->quantidade = $line->quantity;
                            $entry->precoUnitario = $article->unit_price;
                            $entry->funcMov = $line->user()->first()->username;
                            $entry->anulado = false;
                            $entries->push($entry);
                            $entry->save();
                        }
                    }
                    $report->latestUpdate()->stepForward();
                    $report->synced = true;
                    $report->save();
                }
            }
            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error ". $e->getMessage());
        }
    }
}
