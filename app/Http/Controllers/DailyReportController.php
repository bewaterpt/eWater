<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\DailyReports\Report;
use App\Models\DailyReports\ReportLine;
use App\Models\DailyReports\ProcessStatus;
use App\Models\DailyReports\Status;
use App\Models\Connectors\OutonoObras;
use App\Models\Team;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use App\User;
use DateTime;
use Auth;
use DB;
use Log;
use Cache;
use App\Rules\ReportWorkExists;
use App\Rules\VehiclePlate;
use App\Events\ReportStatusUpdated;

class DailyReportController extends Controller
{

    public $workObject;

    /**
     * Class Constructor
     *
     * Initializes class variables, if needed, and, the parent constructor
     */
    public function __contruct()
    {
        parent::__construct();
        $this->workObject = new OutonoObras();
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
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($request->ajax()) {
            $input = $request->input();

            // Get datatable values for sorting, limit and offset
            $sortCol = $input['columns'][$input['order'][0]['column']]['name'];
            $sortDir = $input['order'][0]['dir'];
            $limit = $input['length'];
            $offset = $input['start'];

            // Get searchable columns
            $searchCols = [];
            foreach ($input['columns'] as $value) {
                if ($value['searchable'] === 'true' && $value['search']['value']) {
                    $searchCols[] = [
                        'name' => $value['name'],
                        'value' => $value['search']['value']
                    ];
                } else if ($value['searchable'] === 'true' && $input['search']['value']) {
                    $searchCols[] = [
                        'name' => $value['name'],
                        'value' => $input['search']['value']
                    ];
                }
            }

            // dd($searchCols);

            $reports = Report::select('reports.*');
            if (!$user->isAdmin() && $user->teams()->exists()) {
                $reports->whereIn('team_id', $user->teams->pluck('id'));
            }

            foreach ($searchCols as $searchCol) {
                $reports->where($searchCol['name'], 'rlike', $searchCol['value']);
            }

            // Get row count
            $total = $reports->count();


            // Set filters
            // /* Filter Placeholder */

            // Set limit and offset and get rows
            $reports->orderBy($sortCol, $sortDir)->skip($offset)->take($limit);
            $rows = $reports->get();

            // Add rows to array
            $data = [];
            foreach ($rows as $row) {

                $actions = '';
                if ($this->permissionModel->can('daily_reports.view')) {
                    $actions .= '<a class="text-info edit mr-1" href="' . route('daily_reports.view', ['id' => $row->id]) . '" title="' . trans('general.view') . '"><i class="fas fa-eye"></i></a>';
                }
                if ($this->permissionModel->can('daily_reports.edit')) {
                    $actions .= '<a class="text-info edit" href="' . route('daily_reports.edit', ['id' => $row->id]) . '" title="' . trans('general.edit') . '"><i class="fas fa-edit"></i></a>';
                }

                $info = '';
                if ($row->processStatus->where('error', true)->count() > 0) {
                    $info = '<i class="ri-lg ri-alert-line text-danger" title="' . __('info.report_has_errors') . '"></i>';
                }

                if ($row->inferiorKm()) {
                    $info .= '<i class="fas fa-bullhorn text-warning ml-1" title="' . __('info.report_km_difference') . '"></i>';
                }

                $data[] = [
                    'actions' => $actions,
                    'id' => $row->id,
                    'status' => $row->current_status,
                    'quantity' => $this->helper->decimalHoursToTimeValue($row->getTotalHours()),
                    'driven_km' => $row->driven_km,
                    'team' => $row->team->name,
                    'date' => Carbon::parse($row->date)->format('Y-m-d'),
                    'info' => $info
                ];
            }

            // Create output array
            $output = [
                'draw' => intval($input['draw']),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data,
            ];

            return json_encode($output);
        }

        $statuses = DB::select(DB::raw('SELECT DISTINCT s.id as "id", s.name as "name" from ewater.reports as r
        JOIN(ewater.process_status as ps) on(r.id = ps.process_id)
        JOIN(ewater.statuses as s) on(s.id = ps.status_id)
        where ps.id = (
            SELECT max(id) from ewater.process_status where process_id = r.id
        )'));

        $teams = DB::select(DB::raw('SELECT DISTINCT t.id as "id", t.name as "name" from ewater.reports as r
        JOIN(ewater.teams as t) on(t.id = r.team_id)
        where t.id = r.team_id'));

        return view('daily_reports.index', ['statuses' => $statuses, 'teams' => $teams]);
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
    public function create(Request $request)
    {
        $currentUserTeams = Auth::user()->teams()->get();
        $articles = Article::getDailyReportRelevantArticles()->pluck('designation', 'id');
        $teams = Team::all();

        $workers = null;
        if (Auth::user()->isAdmin()) {
            $workers = User::all();
        } else {
            $workers = User::whereHas('teams', function ($query) use ($currentUserTeams) {
                $query->whereIn('id', $currentUserTeams);
            })->whereHas('roles', function ($query) {
                $query->where('slug', "!=", "admin");
            })->get();
        }


        return view('daily_reports.create', ['articles' => $this->helper->sortArray(array_flip($articles->toArray())), 'workers' => $workers, 'teams' => $currentUserTeams]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $input = $request->json()->all();

        $this->validateJson($request, [
            'plate' => ['required', new VehiclePlate],
            'km_arrival' => ['required'],
            'km_departure' => ['required'],
            'team' => ['required'],
            'rows' => ['required', new ReportWorkExists],
        ]);

        Log::info(sprintf('User %s(%s) is creating a report with the following input data %s', $user->name, $user->username, json_encode($input)));

        try {
            DB::beginTransaction();
            $report = new Report();
            $report->creator()->associate($user->id);
            $report->vehicle_plate = $input['plate'];
            $report->km_departure = $input['km_departure'];
            $report->km_arrival = $input['km_arrival'];
            $report->driven_km = $input['km_arrival'] - $input['km_departure'];
            $report->comment = $input['comment'];
            $report->team()->associate($input['team']);
            $report->save();

            $works = $input['rows'];


            $rows = [];

            foreach ($works as $workNumber => $workData) {
                foreach ($workData as $reportRow) {
                    $rows[] = [
                        'entry_number' => 0,
                        'article_id' => $reportRow['article_id'],
                        'work_number' => $workNumber,
                        'quantity' => $reportRow['quantity'],
                        'entry_date' => (new DateTime($input['datetime']))->format('Y-m-d H:i:s'),
                        'report_id' => $report->id,
                        'created_by' => $user->id,
                        'user_id' => $reportRow['worker'],
                        'worker' => $reportRow['worker'],
                        'driven_km' => $reportRow['driven_km'],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                }
            }

            $processCreated = new ProcessStatus();
            $processCreated->report()->associate($report->id);
            $processCreated->status()->associate(1);
            $processCreated->user()->associate($user->id);
            $processCreated->concluded_at = Carbon::now();
            $processCreated->save();

            $processStatus = $processCreated->stepForward();

            $report->current_status = $processStatus->status()->first()->name;
            $report->save();
            ReportLine::insert($rows);
            DB::commit();

            Log::info(sprintf('User %s(%s) created report with id %d having %d lines', $user->name, $user->username, $report->id, sizeof($rows)));
            Log::info(sprintf('User %s(%s) created the following lines %s', $user->name, $user->username, json_encode($rows)));
            ReportStatusUpdated::dispatch($report);
        } catch (\PDOException $e) {
            DB::rollBack();
            return redirect(route('daily_reports.list'))->withErrors(__('errors.unexpected_error'), 'custom');
        }
        return route('daily_reports.list');
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
    public function view(Request $request, $reportId)
    {
        $this->workObject = new OutonoObras;
        $report = Report::find($reportId);
        $user = Auth::user();
        $statusObject = new Status();

        if (!$report) {
            return redirect()->back()->withErrors(__('errors.report_not_found', ['reportId' => $reportId]), 'custom');
        }

        if (!$user->teams()->get()->contains($report->team_id) && !$user->isAdmin()) {
            return redirect()->back()->withErrors(__('errors.dont_belong_to_report_team', ['reportId' => $reportId]), 'custom');
        }

        $processStatuses = $report->processStatus->sortByDesc('created_at')->sortByDesc('id');


        return view('daily_reports.view', ['report' => $report, 'processStatuses' => $processStatuses, 'workObject' => $this->workObject, 'statusObj' => $statusObject]);
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
    public function getArticlePrice(Request $request)
    {
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
    public function progressStatus(Request $request, $progressStatusId)
    {
        $processStatus = ProcessStatus::find($progressStatusId);
        if ($request->input('comment') !== "") {
            $processStatus->comment = $request->input('comment');
            $processStatus->save();
        }
        $newProcessStatus = $processStatus->stepForward();
        // Log::info(sprintf('User %s(%s) progressed report with id %d to state %s(%s) lines.', Auth::user()->name, Auth::user()->username, $processStatus->report()->first()->id, $newProcessStatus->status()->first()->name, $newProcessStatus->status()->first()->slug));

        if ($newProcessStatus->status()->first()->id === $processStatus->getStatusDBSync()) {
            try {
                $output = null;
                Artisan::call('reports:sync', ['reports' => $processStatus->report()->first()->id], $output);
                $newProcessStatus->comment = __('general.daily_reports.db_sync_success');
                $newProcessStatus->save();
                $newProcessStatus->stepForward();
            } catch (\Exception $e) {
                $newProcessStatus->comment = __('errors.db_sync_failed', ['msg' => '<b>' . $e->getMessage() . '</b> at line <b>' . $e->getline() . '</b><br><br>Stack trace: <br>' . $e->getTraceAsString()]);
                $newProcessStatus->error = true;
                $newProcessStatus->save();
                $newProcessStatus->stepBack();
                return redirect()->back()->withErrors(__('errors.db_sync_failed', ['msg' => '<b>' . $e->getMessage() . '</b> at line <b>' . $e->getline() . '</b>']), 'custom');
            }
        }

        // Log::info(sprintf('User %s(%s) progressed report with id %d to state %s(%s) lines.', Auth::user()->name, Auth::user()->username, $processStatus->report()->first()->id, $newProcessStatus->status()->first()->name, $newProcessStatus->status()->first()->slug));

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
    public function regressStatus(Request $request, $progressStatusId)
    {
        $processStatus = ProcessStatus::find($progressStatusId);
        if ($request->input('comment') !== "") {
            $processStatus->comment = $request->input('comment');
            $processStatus->save();
        }
        $newProcessStatus = $processStatus->stepBack();

        return redirect()->back();
    }

    public function progressExtra(Request $request, $progressStatusId)
    {
        $processStatus = ProcessStatus::find($progressStatusId);
        if ($request->input('comment') !== "") {
            $processStatus->comment = $request->input('comment');
            $processStatus->save();
        }
        $processStatus->stepExtra();
        $processStatus->stepBack();

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
    public function cancel(Request $request, $reportId)
    {
        $report = Report::find($reportId);

        if ($report->closed()) {
            return redirect()->back();
        } else {
            $report->cancel();

            // TODO: Add translation string
            return redirect()->back()->with(['success' => 'Report successfuly canceled']);
        }
    }

    public function getProcessStatusComment(Request $request)
    {
        $data = [
            'status' => 500,
            'msg' => 'Unexpected error',
        ];

        $processStatus = ProcessStatus::find($request->json('id'));

        if ($processStatus) {
            $data['status'] = 200;
            $data['msg'] = 'Success';
            $data['content'] = $processStatus->comment;
        } else {
            $data['status'] = 404;
            $data['msg'] = 'Not Found';
        }

        return json_encode($data);
    }

    public function edit(Request $request, $reportId)
    {
        $report = Report::find($reportId);
        $currentUserTeams = Auth::user()->teams()->pluck('id');
        $articles = Article::getDailyReportRelevantArticles()->pluck('id', 'designation');
        $workers = null;

        if ($report->getCurrentStatus()->first()->userCanProgress()) {
        }

        if (Auth::user()->isAdmin()) {
            $workers = User::all();
        } else {
            $workers = User::whereHas('teams', function ($query) use ($currentUserTeams) {
                $query->whereIn('id', $currentUserTeams);
            })->whereHas('roles', function ($query) {
                $query->where('slug', "!=", "admin");
            })->get();
        }

        $teams = Team::all();

        $works = [];
        $worksTranportData = [];

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

        foreach ($works as $work) {
            $works[$work[0]->work_number] = collect($work);
        }

        return view('daily_reports.edit', ['report' => $report, 'workers' => $workers, 'articles' => $articles, 'works' => $works, 'transportData' => $worksTranportData, 'teams' => $teams]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $input = $request->json()->all();

        $this->validateJson($request, [
            'plate' => ['required', new VehiclePlate],
            'km_arrival' => ['required'],
            'km_departure' => ['required'],
            'team' => ['required'],
            'rows' => ['required', new ReportWorkExists],
        ]);

        try {
            DB::beginTransaction();
            $report = Report::find($request->id);
            $report->vehicle_plate = $input['plate'];
            $report->km_departure = $input['km_departure'];
            $report->km_arrival = $input['km_arrival'];
            $report->driven_km = $input['km_arrival'] - $input['km_departure'];
            $report->comment = $input['comment'];
            $report->date = (new DateTime($input['datetime']))->format('Y-m-d H:i:s');
            $report->team()->associate($input['team']);
            $report->save();

            $works = $input['rows'];

            $report->lines()->get()->map(function ($line) use ($works) {
                return $line->delete();
            });

            $rows = [];

            foreach ($works as $workNumber => $workData) {

                foreach ($workData as $reportRow) {

                    $rows[] = [
                        'entry_number' => 0,
                        'article_id' => $reportRow['article_id'],
                        'work_number' => $workNumber,
                        'quantity' => $reportRow['quantity'],
                        'entry_date' => (new DateTime($input['datetime']))->format('Y-m-d H:i:s'),
                        'report_id' => $report->id,
                        'created_by' => $user->id,
                        'user_id' => $reportRow['worker'],
                        'worker' => $reportRow['worker'],
                        'driven_km' => $reportRow['driven_km'],
                    ];
                }
            }

            ReportLine::insert($rows);
            DB::commit();

            ReportStatusUpdated::dispatch($report);
        } catch (\PDOException $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(__('errors.unexpected_error'), 'custom');
        }
        return route('daily_reports.view', ['id' => $request->id]);
    }

    public function restore(Request $request, $progressStatusId)
    {
        $processStatus = ProcessStatus::find($progressStatusId);
        $processStatus->restore();

        return redirect()->back();
    }

    public function previous(Request $request) {
        $report = Report::find($request->id);

        if ($report->isFirst()) {
            return redirect()->back()->withErrors(__('errors.last_report', ['id' => $report->id]), 'custom');
        }

        $rid = $report->getPreviousId();

        return $rid ? redirect(route('daily_reports.view', ['id' => $rid])) : redirect()->back()->withErrors(__('errors.could_not_calculate_previous_report_id'), 'custom');
    }

    public function next(Request $request) {
        $report = Report::find($request->id);

        if ($report->isLast()) {
            return redirect()->back()->withErrors(__('errors.first_report', ['id' => $report->id]), 'custom');
        }

        $rid = $report->getNextId();

        return $rid ? redirect(route('daily_reports.view', ['id' => $rid])) : redirect()->back()->withErrors(__('errors.could_not_calculate_next_report_id'), 'custom');
    }

}
