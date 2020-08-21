<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReports\Report;
use App\Models\Connectors\OutonoArtigos as Artigos;
use App\Models\Connectors\OutonoObrasCC as ObrasCC;
use Illuminate\Support\Carbon;

class TestController extends Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {
        // $reports = Report::notSynced()->get();

        // $collectionOfLines = $reports->map(function ($report) {
        //     return $report->lines()->get()->map(function ($line) {
        //         return $line;
        //     });
        // });

        // $reportLines;
        // $reportTransportationData;

        // foreach ($collectionOfLines as $lines) {
        //     foreach($lines as $line) {
        //         $reportLines[$line->work_number][] = $line;

        //         if (!isset($reportTransportationData[$line->work_number]['km'])) {
        //             $reportTransportationData[$line->work_number]['km'] = $line->driven_km;
        //         }

        //         if (!isset($reportTransportationData[$line->work_number]['date'])) {
        //             $reportTransportationData[$line->work_number]['date'] = $line->entry_date;
        //         }

        //         if (!isset($reportTransportationData[$line->work_number]['report'])) {
        //             $reportTransportationData[$line->work_number]['report'] = $line->report()->first();
        //         }

        //     }
        // }

        // $reportLines = collect($reportLines);
        // $reportTransportationData = collect($reportTransportationData);

        // // dd($reportTransportationData);

        // foreach($reportLines as $work_number => $lines) {
        //     $transportEntry = new obrasCC();
        //     $transportArticle = Artigos::getTransportationArticle();
        //     $transportEntry->numLanc = $transportEntry->lastInsertedEntryNumber()+1;
        //     $transportEntry->dtMov = Carbon::now()->format('Y-m-d h:i:s');
        //     $transportEntry->clMov = $transportArticle->cod;
        //     $transportEntry->tpMov = 'D';
        //     $transportEntry->numObra = $work_number;
        //     $transportEntry->dtDoc = $reportTransportationData[$work_number]['date'];
        //     $transportEntry->quantidade = $reportTransportationData[$work_number]['km'];
        //     $transportEntry->precoUnitario = $transportArticle->precoUnitario;
        //     $transportEntry->funcMov = $reportTransportationData[$work_number]['report']->creator()->first()->username;
        //     $transportEntry->anulado = false;
        //     $transportEntry->save();

        //     foreach ($lines as $line) {
        //         $entry = new obrasCC();
        //         $article = Artigos::getById($line->article_id);
        //         $entry->numLanc = $entry->lastInsertedEntryNumber()+1;
        //         $entry->dtMov = Carbon::now()->format('Y-m-d h:i:s');
        //         $entry->clMov = $article->cod;
        //         $entry->tpMov = 'D';
        //         $entry->numObra = $work_number;
        //         $entry->dtDoc = $line->entry_date;
        //         $entry->quantidade = $line->quantity;
        //         $entry->precoUnitario = $article->precoUnitario;
        //         $entry->funcMov = $line->user()->first()->username;
        //         $entry->anulado = false;
        //         $entry->save();
        //     }
        // }



        return intval(parent::workExists($request));
    }
}
