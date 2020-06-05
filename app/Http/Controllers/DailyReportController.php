<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport\Article;
use App\Models\DailyReport\Report;
use App\Models\DailyReport\OutonoObrasCCConnector;
use App\Models\DailyReport\ReportLine;
use App\Models\DailyReport\ProcessStatus;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use DateTime;
use Auth;

class DailyReportController extends Controller
{
    public function __contruct() {
        parent::__construct();
    }

    public function index() {
        $user = Auth::user();

        $rawReports = $user->reports()->get()->toArray();
        $reports = new Collection();

        if ($this->statusModel->userCanApprove(2)) {
            $reports = array_merge($reports, Report::where('current_status', 2)->get()->toArray());
        }

        if ($this->statusModel->usercanApprove(3)) {
            $reports = array_merge($reports, Report::where('current_status', 3)->get()->toArray());
        }

        foreach ($rawReports as $rawReport) {
            $reports->push((object) $rawReport);
        }

        return view('daily_reports.index', ['reports' => $reports]);
    }

    public function create(Request $request) {
        if($request->isMethod('POST')) {
            $user = Auth::user();

            $report = new Report();
            $report->creator()->associate($user->id);
            $report->save();

            $input = $request->input();
            $lastInsertedEntryNumber = OutonoObrasCCConnector::lastInsertedEntryNumber() + 1;

            $rows = [];

            for ($i = 0; $i < Sizeof($input['work-number']); $i++) {
                $rows[] = [
                    'entry_number' => $lastInsertedEntryNumber,
                    'article_id' => $input['article'][$i],
                    'work_number' => $input['work-number'][$i],
                    'unit_price' => $input['unit-price'][$i],
                    'quantity' => $input['quantity'][$i],
                    'entry_date' => (new DateTime($input['datetime'][$i]))->format('Y-m-d H:i:s'),
                    'report_id' => $report->id,
                    'user_id' => $user->id
                ];

                $lastInsertedEntryNumber++;
            }

            $processCreated = new ProcessStatus();
            $processCreated->report()->associate($report->id);
            $processCreated->status()->associate(1);
            $processCreated->user()->associate($user->id);
            $processCreated->concluded_at = Carbon::now();
            $processCreated->save();

            $processEditing = new ProcessStatus();
            $processEditing->report()->associate($report->id);
            $processEditing->status()->associate(1);
            $processEditing->user()->associate($user->id);
            $processEditing->previous()->associate($processCreated->id);
            $processEditing->save();

            ReportLine::insert($rows);

            return view('daily_reports.list')->with(['success' => __('general.daily_reports.report_success')]);

        } else {
            $articles = Article::all()->pluck('cod', 'descricao');
            return view('daily_reports.create', ['articles' => $this->helper->sortArray($articles->toArray())]);
        }

    }

    public function view() {
    }

    public function getArticlePrice(Request $request) {
        $data = [
            'status' => 500,
            'msg' => 'Unexpected error',
        ];

        $article = Article::find($request->input('id'));

        if ($article) {
            $data['status'] = 200;
            $data['msg'] = 'Success';
            $data['article'] = $article;
        } else {
            $data['status'] = 404;
            $data['msg'] = 'Not Found';
        }

        return json_encode($data);
    }
}
