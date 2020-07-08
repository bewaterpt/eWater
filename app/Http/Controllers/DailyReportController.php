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
use Illuminate\Support\Facades\Artisan;
use App\User;
use DateTime;
use Auth;
use Route;
use Illuminate\Console\Command;

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

        $reports = Report::all();
        $reportList = collect([]);

        // foreach($reports as $report) {
        //     if ($report->creator()->first()->id === $user->id || $this->statusModel->userCanProgress($report->getCurrentStatus()->first()->id) && !$report->closed()) {
        //         $reportList->push($report);
        //     }
        // }

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
            $input = $request->json()->all();

            $report = new Report();
            $report->creator()->associate($user->id);
            $report->vehicle_plate = $input['plate'];
            $report->km_departure = $input['km_departure'];
            $report->km_arrival = $input['km_arrival'];
            $report->comment = $input['comment'];
            $report->save();

            $works = $input['rows'];

            // dd($works);

            $lastInsertedEntryNumber = OutonoObrasCCConnector::lastInsertedEntryNumber() + 1;

            $rows = [];

            foreach ($works as $workNumber => $workData) {

                foreach ($workData as $reportRow) {
                    $rows[] = [
                        'entry_number' => $lastInsertedEntryNumber,
                        'article_id' => $reportRow['article'],
                        'work_number' => $workNumber,
                        'quantity' => $reportRow['quantity'],
                        'entry_date' => (new DateTime($reportRow['datetime']))->format('Y-m-d H:i:s'),
                        'report_id' => $report->id,
                        'created_by' => $user->id,
                        'user_id' => $reportRow['worker'],
                        'driven_km' => $reportRow['driven-km'],
                        'worker' => $reportRow['worker'],
                    ];

                    $lastInsertedEntryNumber++;
                }
            }

            $processCreated = new ProcessStatus();
            $processCreated->report()->associate($report->id);
            $processCreated->status()->associate(1);
            $processCreated->user()->associate($user->id);
            $processCreated->concluded_at = Carbon::now();
            $processCreated->save();

            $processCreated->stepForward();

            ReportLine::insert($rows);

            return route('daily_reports.list');

        } else {
            $currentUserRoles = Auth::user()->roles()->pluck('slug');
            $articles = Article::getDailyReportRelevantArticles()->pluck('cod', 'descricao');
            $workers = User::whereHas('roles', function ($query) {
                $query->whereIn('slug', $currentUserRoles);
            })->get();
            return view('daily_reports.create', ['articles' => $this->helper->sortArray($articles->toArray()), 'workers' => $workers]);
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
        $user = Auth::user();

        if (!$report) {
            return redirect()->back()->withErrors(__('errors.report_not_found'), 'custom');
        }

        // if ($report->creator()->first()->id !== $user->id && !$this->statusModel->userCanProgress($report->getCurrentStatus()->first()->id)) {
        //     return redirect(route('daily_reports.list'))->withErrors(__('auth.permission_denied', ['route' => $request->path()]), 'custom');
        // }

        // dd($report->lines()->get()->groupBy('work_number'));
        $processStatuses = $report->processStatus()->orderBy('created_at', 'desc')->orderBy('id', 'desc')->get();

        return view('daily_reports.view', [ 'report' => $report, 'processStatuses' => $processStatuses ]);
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
        $newProcessStatus = $processStatus->stepForward();

        if($newProcessStatus->status()->first()->id === ProcessStatus::STATUS_DB_SYNC) {
            try {
                Artisan::call('reports:sync ' . $processStatus->report()->first()->id);
                $newProcessStatus->comment = __('general.daily_reports.db_sync_success');
                $newProcessStatus->save();
                $newProcessStatus->stepForward();
            } catch (\Exception $e) {
                $newProcessStatus->comment = __('errors.db_sync_failed') . '<b>' . $e->getMessage() . '</b>';
                $newProcessStatus->error = true;
                $newProcessStatus->save();
                $newProcessStatus->stepBack();
                return redirect()->back()->withErrors(__('errors.db_sync_failed') . '<b>' . $e->getMessage() . '</b>', 'custom');
            }
        }

        return redirect()->back()->with(__('general.daily_reports.db_sync_success'));
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

    public function getProcessStatusComment(Request $request) {
        $data = [
            'status' => 500,
            'msg' => 'Unexpected error',
        ];

        $processStatus = ProcessStatus::find($request->json('id'));

        if ($processStatus) {
            $data['status'] = 200;
            $data['msg'] = 'Success';
            $data['comment'] = $processStatus->comment;
        } else {
            $data['status'] = 404;
            $data['msg'] = 'Not Found';
        }

        return json_encode($data);
    }

    public function edit(Request $request, $reportId) {
        $report = Report::find($reportId);
        $currentUserRoles = Auth::user()->roles()->pluck('slug');
        $articles = Article::getDailyReportRelevantArticles()->pluck('cod', 'descricao');
        $workers = User::whereHas('roles', function ($query) {
            $query->whereIn('slug', $currentUserRoles);
        })->get();

        $works;
        $worksTranportData;

        foreach ($report->lines()->get() as $line) {
            $works[$line->work_number][] = $line;

            if (!isset($worksTranportData[$line->work_number]['km'])) {
                $worksTranportData[$line->work_number]['km'] = $line->driven_km;
            }

            if (!isset($worksTranportData[$line->work_number]['date'])) {
                $worksTranportData[$line->work_number]['date'] = $line->entry_date;
            }

            if (!isset($worksTranportData[$line->work_number]['report'])) {
                $worksTranportData[$line->work_number]['report'] = $line->report()->first();
            }
        }

        return view('daily_reports.edit', ['report' => $report, 'workers' => $workers, 'articles' => $articles, 'works' => $works, 'transportData' => $worksTranportData]);
    }
}
