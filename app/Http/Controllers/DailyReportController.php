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
    /**
     * Class Constructor
     *
     * Initializes class variables, if needed, and, the parent constructor
     */
    public function __contruct() {
        parent::__construct();
    }

    /**
     * @method index
     *
     * Presents the user with a list of reports.
     *
     * @param Request $request
     *
     * @return View view()
     */
    public function index(Request $request) {
        $user = Auth::user();

        $reports =  $user->reports()->get();

        if ($this->statusModel->userCanProgress(4)) {
            $newReports = Report::all();
            foreach($newReports as $newReport) {
                if ($newReport->latestUpdate()->status()->first()->id === 4) {
                    $reports->push($newReport);
                }
            }
        }

        if ($this->statusModel->usercanProgress(5)) {
            $newReports = Report::all();
            foreach($newReports as $newReport) {
                if ($newReport->latestUpdate()->status()->first()->id === 5) {
                    $reports->push($newReport);
                }
            }
        }

        return view('daily_reports.index', ['reports' => $reports]);
    }

    /**
     * @method create
     *
     * Presents the user with the create report page, or creates a new report.
     * Action based on request method.
     *
     * @param Request $request
     *
     * @return Redirect redirect()->back()
     * or
     * @return View view()
     */
    public function create(Request $request) {

        if($request->isMethod('POST')) {
            $user = Auth::user();

            if(!$user) {
                $user = Auth::loginUsingId($request->query('user_id'));
            }

            $report = new Report();
            $report->creator()->associate($user->id);
            $report->km_departure = $request->input('km-departure');
            $report->km_arrival = $request->input('km-arrival');
            $report->vehicle_plate = $request->input('plate');
            $report->save();

            $works = $request->json()->all();
            dd($works);

            // $rows = [];
            // $i = 0;

            foreach ($works as $work) {
                $i = 0;
                // $rows[] = [
                //     'work_number' => $work,
                //     'article_id' =>
                // ];

            }

            dd($rows);

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
                    'user_id' => $user->id,
                    'driven_km' => $input['driven_km'][$input['work-number'][$i]],
                ];

                $lastInsertedEntryNumber++;
            }

            $processCreated = new ProcessStatus();
            $processCreated->report()->associate($report->id);
            $processCreated->status()->associate(1);
            $processCreated->user()->associate($user->id);
            $processCreated->concluded_at = Carbon::now();
            $processCreated->save();

            $processCreated->stepForward();

            ReportLine::insert($rows);

            return redirect(route('daily_reports.list'))->with(['success' => __('general.daily_reports.report_success')]);

        } else {
            $articles = Article::getDailyReportRelevantArticles()->pluck('cod', 'descricao');
            return view('daily_reports.create', ['articles' => $this->helper->sortArray($articles->toArray())]);
        }

    }

    /**
     * @method view
     *
     * Fetches the specified report data and presents it to the user.
     *
     * @param Request $request
     * @param Int $reportId
     *
     * @return View view()
     */
    public function view(Request $request, $reportId) {
        $report = Report::find($reportId);
        $reportRows = $report->processStatus()->orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();

        return view('daily_reports.view', [ 'report' => $report, 'reportRows' => $reportRows ]);
    }

    /**
     * @method getArticlePrice
     *
     * AJAX method that is accessed when the user choses an Article from the article list.
     *
     * @param Request $request
     *
     * @return Array $data
     */
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

    /**
     * @method progressStatus
     *
     * Progresses report to next status
     *
     * @param Request $request
     * @param Int $processStatusId
     */
    public function progressStatus(Request $request, $progressStatusId) {
        $processStatus = ProcessStatus::find($progressStatusId);
        if ($request->input('comment') !== "") {
            $processStatus->comment = $request->input('comment');
            $processStatus->save();
        }
        $processStatus->stepForward();

        return redirect()->back();
    }

    /**
     * @method regressStatus
     *
     * Regresses report to previous status
     *
     * @param Request $request
     * @param Int $processStatusId
     */
    public function regressStatus(Request $request, $progressStatusId) {
        $processStatus = ProcessStatus::find($progressStatusId);
        if ($request->input('comment') !== "") {
            $processStatus->comment = $request->input('comment');
            $processStatus->save();
        }
        $processStatus->stepBack();

        return redirect()->back();
    }

    public function progressExtra(Request $request, $progressStatusId) {
        $processStatus = ProcessStatus::find($progressStatusId);
        if ($request->input('comment') !== "") {
            $processStatus->comment = $request->input('comment');
            $processStatus->save();
        }
        $processStatus->stepExtra();

        return redirect()->back();
    }

    /**
     * @method cancel
     *
     * Stops further execution of process operations and closes the report in a cancelled state.
     *
     * @param Request $request
     * @param Int $reportId
     */
    public function cancel(Request $request, $reportId) {
        $report = Report::find($reportId);

        if($report->closed()) {
            return redirect()->back();
        } else {
            $report->cancel();

            // TODO: Add translation string
            return redirect()->back()->with(['success' => 'Report successfuly canceled']);
        }
    }
}
