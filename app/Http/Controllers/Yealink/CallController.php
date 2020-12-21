<?php

namespace App\Http\Controllers\Yealink;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yealink\Pbx;
use App\Models\Delegation;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\Helper;
use Auth;
use DB;
use Cache;
use Artisan;
use Illuminate\Support\Facades\Redis;
use App\Models\Yealink\CDRRecord;
use Illuminate\Support\Carbon;
use Symfony\Component\Process\Process;
use App\Exports\CDRRecordExport;

class CallController extends Controller
{

    protected $agents = [
        110 => 'Abel Gomes',
        113 => 'Natália Inácio',
        114 => 'Manuela Dias',
        115 => 'Ana Reis',
        116 => 'Mónica Dinis',
        117 => 'Manuel Henriques',
    ];

    protected $agentsForQuery = null;

    protected $dateFormat = 'Y-m-d H:i:s';

    private $carbon;

    private $operators = [
        'timestart' => 'rlike',
        'callfrom' => 'like',
        'callto' => 'like',
        'callduration' => 'like',
        'talkduration' => 'like',
        'waitduration' => 'like',
        'status' => 'like',
        'type' => 'like',
    ];

    // private $logicalOps = [
    //     'timestart' => 'like',
    //     'callfrom' => 'like',
    //     'callto' => 'like',
    //     'callduration' => 'like',
    //     'talkduration' => 'like',
    //     'waitduration' => 'like',
    //     'status' => 'like',
    //     'type' => 'like',
    // ];

    public function __construct() {
        parent::__construct();

        $this->agentsForQuery = implode('|', array_keys($this->agents));
        $this->carbon = new Carbon();
    }

    public function pbxList(Request $request) {
        $pbxList = Pbx::all();

        // dd($request);

        return view('calls.pbx.index', ['pbxList' => $pbxList]);
    }

    public function pbxEdit(Request $request, $id) {
        $pbx = Pbx::find($id);
        $delegations = Delegation::all();
        $pbx->password = Crypt::decryptString($pbx->password);

        return view('calls.pbx.edit', ['pbx' => $pbx, 'delegations' => $delegations]);
    }

    public function pbxUpdate(Request $request, $id) {
        $pbx = Pbx::find($id);

        $input = (object) $request->input();

        $pbx->designation = $input->designation;
        $pbx->protocol = $input->protocol;
        $pbx->url = $input->url;
        $pbx->port = $input->port;
        $pbx->api_base_uri = $input->api_base;
        $pbx->username = $input->api_username;
        $pbx->password = Crypt::encryptString($input->api_password);
        $pbx->delegation()->associate($input->delegation);
        $pbx->save();

        return redirect(route('calls.pbx.list'));
    }

    public function pbxCreate() {
        $delegations = Delegation::all();
        return view('calls.pbx.create', ['delegations' => $delegations]);
    }

    public function pbxStore(Request $request) {
        $user = Auth::user();

        $input = (object) $request->input();

        if (!Pbx::where('url', $input->url)->first()) {
            $pbx = new Pbx();
            $pbx->designation = $input->designation;
            $pbx->protocol = $input->protocol;
            $pbx->url = $input->url;
            $pbx->port = $input->port;
            $pbx->api_base_uri = $input->api_base;
            $pbx->username = $input->api_username;
            $pbx->password = Crypt::encryptString($input->api_password);
            $pbx->delegation()->associate($input->delegation);
            $pbx->save();
        } else {
            return redirect()->back()->withErrors(__('errors.pbx_already_exists'), 'custom');
        }

        return redirect(route('calls.pbx.list'));
    }

    public function index(Request $request) {

        if ($request->ajax()) {
            $input = $request->input();

            // Get datatable values for sorting, limit AND offset
            $sortCol = $input['columns'][$input['order'][0]['column']]['name'];
            $sortDir = $input['order'][0]['dir'];
            $limit = $input['length'];
            $offset = $input['start'];
            $draw = $input['draw'];
            $searchCols = [];

            // Get searchable columns
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

            $cachedResults = Cache::remember('datatable_calls_records', 54000, function () use ($searchCols) {

                $cdrIds = $this->getInboundAndTransferCalls();

                $cdrs = "SELECT * FROM cdr_records as cdrAll WHERE cdrAll.callid IN(\"" . implode('", "', $cdrIds) . "\")";

                // Set filters
                foreach ($searchCols as $searchCol) {
                    if ($searchCol['name'] === 'timestart') {
                        $searchCol['value'] = Carbon::parse($searchCol['value'])->format('Y-m-d');
                    }

                    $cdrs .= " AND cdrAll.{$searchCol['name']} RLIKE '{$searchCol['value']}'";
                }

                $rows = collect(DB::select($cdrs));

                // Cache result
                return $rows;
            });

            // Add rows to array
            $data = [];


            foreach ($searchCols as $searchCol) {
                if ($searchCol['name'] === 'timestart') {
                    $searchCol['value'] = Carbon::parse($searchCol['value'])->format('Y-m-d');
                }

                $cachedResults = $cachedResults->filter(function ($result) use ($searchCol) {
                    return preg_match('/' . $searchCol['value'] . '/', $result->{$searchCol['name']});
                });
            }

            $total = $cachedResults->count();

            // Set order, limit and offset
            $cachedResults = $cachedResults->sortBy($sortCol, SORT_REGULAR, $sortDir === 'desc' ? true : false)->skip($offset)->take($limit);

            foreach ($cachedResults as $row) {

                $data[] = [
                    // 'actions' => $actions,
                    'timestart' => $row->timestart,
                    'callfrom' => $row->callfrom,
                    'callto' => $row->callto,
                    'callduration' => Helper::decimalSecondsToTimeValue($row->callduration, false, true),
                    'talkduration' => Helper::decimalSecondsToTimeValue($row->talkduration, false, true),
                    'waitduration' => Helper::decimalSecondsToTimeValue($row->waitduration, false, true),
                    'status' => $row->status,
                    'type' => $row->type,
                    // 'actions' => $actions
                ];
            }

            // Create output array
            $output = [
                'draw' => intval($draw),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data
            ];

            return json_encode($output);
        }

        return view('calls.index');
    }

    public function getMonthlyWaitTimeInfo(Request $request) {
        $data = [
            'status' => 500,
            'message' => __('errors.unexpected_error'),
        ];

        $input = (object) $request->json()->all();

        $waitDurations = [];

        $cdrIds = $this->getInboundAndTransferCalls();

        $cdrs = collect(DB::select("SELECT monthname(cdrAll.timestart) month, cdrAll.waitduration FROM cdr_records as cdrAll WHERE cdrAll.callid IN(\"" . implode('", "', $cdrIds) . "\")"));

        $cdrs->map(function ($item) use (&$waitDurations){
            $waitDurations[$item->month][] = $item->waitduration;
        });

        $waitDurations = collect($waitDurations);

        $months = CDRRecord::selectRaw('monthname(timestart) month')
                    ->distinct('month')
                    ->whereBetween('timestart', [Carbon::now()->subMonths(12)->format($this->dateFormat), Carbon::now()->format($this->dateFormat)])
                    ->get()
                    ->map(function($item) {
                        return $item->month;
                    })->toArray();

        foreach ($months as $month) {
            $data['test_values'][] = $waitDurations->get($month);
            $data['wavg'][] = round(Helper::weightedAverage($waitDurations->get($month)));
            $data['max'][] = max($waitDurations->get($month));
            $data['avg'][] = round(array_sum($waitDurations->get($month)) / count($waitDurations->get($month)));
        }

        $data['labels'] = $months;
        // $data['total'] = $total;
        $data['status'] = 200;
        $data['message'] = 'Success';

        // dd($data);
        // dd($data);

        return json_encode($data);
    }

    public function getMonthlyCallNumberInfo() {

        $cdrs = CDRRecord::selectRaw('distinct count(callid) as count, monthname(timestart) month')
                // ->join('cdr_records as cdrComp', function ($join) {
                //     $join->on('cdr.callid', '=', 'cdrComp.callid')->on('cdr.callduration', '<', 'cdrComp.callduration');
                // })
                ->where('status', '<>', 'NO ANSWER')
                ->groupBy('month');

        $total = [];
        $cdrsTotal = clone $cdrs;
        $cdrsTotal->get()->map(function($item) use (&$total){
            $total[$item->month][] = $item->count;
        });

        $cdrsFront = clone $cdrs;
        $cdrsFront->where('callto', 'rlike', $this->agentsForQuery)->where('type', '<>', 'Internal');

        $frontOffice = [];
        $cdrsFront->get()->map(function($item) use (&$frontOffice){
            $frontOffice[$item->month][] = $item->count;
        });

        $cdrsGeneric = clone $cdrs;
        $cdrsGeneric->where('callto', 'not rlike', $this->agentsForQuery)->where('type', '<>', 'Internal');

        $generic = [];
        $cdrsGeneric->get()->map(function($item) use (&$generic){
            $generic[$item->month][] = $item->count;
        });

        $cdrsInternal = clone $cdrs;
        $cdrsInternal->where('type', 'Internal');

        $internal = [];
        $cdrsInternal->get()->map(function($item) use (&$internal){
            $internal[$item->month][] = $item->count;
        });

        $cdrsLost = CDRRecord::selectRaw('distinct count(callid) as count, monthname(timestart) month')
                    ->where('status', 'NO ANSWER')
                    ->groupBy('month');

        $totalLost = [];
        $cdrsLost->get()->map(function($item) use (&$totalLost){
            $totalLost[$item->month][] = $item->count;
        });

        $cdrsFrontLost = clone $cdrsLost;
        $cdrsFrontLost->where('callto', 'rlike', $this->agentsForQuery)->where('type', '<>', 'Internal');

        $frontOfficeLost = [];
        $cdrsFrontLost->get()->map(function($item) use (&$frontOfficeLost){
            $frontOfficeLost[$item->month][] = $item->count;
        });

        $cdrsGenericLost = clone $cdrsLost;
        $cdrsGenericLost->where('callto', 'not rlike', $this->agentsForQuery)->where('type', '<>', 'Internal');

        $genericLost = [];
        $cdrsGenericLost->get()->map(function($item) use (&$genericLost){
            $genericLost[$item->month][] = $item->count;
        });

        $cdrsInternalLost = clone $cdrsLost;
        $cdrsInternalLost->where('type', 'Internal');

        $internalLost = [];
        $cdrsInternalLost->get()->map(function($item) use (&$internalLost){
            $internalLost[$item->month][] = $item->count;
        });

        $months = CDRRecord::selectRaw('monthname(timestart) month')
                ->distinct('month')
                ->whereBetween('timestart', [Carbon::now()->subMonths(12)->format($this->dateFormat), Carbon::now()->format($this->dateFormat)])
                ->get()
                ->map(function($item) {
                    return $item->month;
                })->toArray();

        foreach ($months as $month) {
            $data['total'][] = !isset($total[$month]) ? 0 : $total[$month][0];
            $data['frontOffice'][] = !isset($frontOffice[$month]) ? 0 : $frontOffice[$month][0];
            $data['generic'][] = !isset($generic[$month]) ? 0 : $generic[$month][0];
            $data['internal'][] = !isset($internal[$month]) ? 0 : $internal[$month][0];
            $data['totalLost'][] = !isset($totalLost[$month]) ? 0 : $totalLost[$month][0];
            $data['frontOfficeLost'][] = !isset($frontOfficeLost[$month]) ? 0 : $frontOfficeLost[$month][0];
            $data['genericLost'][] = !isset($genericLost[$month]) ? 0 : $genericLost[$month][0];
            $data['internalLost'][] =!isset($internalLost[$month]) ? 0 :  $internalLost[$month][0];
        }

        $data['labels'] = $months;

        // dd($data);

        return json_encode($data);
    }

    public function export(Request $request, $filetype = 'csv') {
        $renderer = false;

        if ($filetype == 'pdf') {
            $renderer = \Maatwebsite\Excel\Excel::MPDF;
        }

        /**
         * @TODO switch to memory friendly excel export
         */
        // $cdrIds = $this->getInboundAndTransferCalls();

        // $cdrs = "SELECT * FROM cdr_records as cdrAll WHERE cdrAll.callid IN(\"" . implode('", "', $cdrIds) . "\")";

        // $cdrs = collect(DB::select($cdrs));

        // return (new FastExcel($cdrs))->download(__('calls.call_records') . '.' . $filetype);
        // Excel::create(__('calls.call_records') . '.' . $filetype, function ($excel) {
        //     $excel->sheet('sheet1', function ($sheet) {
        //         CDRRecord::chunk(100, function ($cdr) use ($sheet) {
        //             $cdrArray = $cdr->toArray();
        //             $sheet->appendRow($cdrArray);
        //         });
        //     });
        // })->download($filetype);

        return (new CDRRecordExport(null))->download(__('calls.call_records') . '.' . $filetype, ($renderer ? $renderer : null), ['X-ewater-filename' => __('calls.call_records') . '.' . $filetype]);
    }

    public function refetch() {
        $data = [
            'status' => 200,
            'message' => 'OK'
        ];

        try {
            if (intval(Redis::hget('calls', 'updating')) === 1) {
                $data['status'] = 202;
                $data['message'] = __('errors.call_sync_in_progress');
            } else {
                Artisan::call('calls:get');
                // $p->start();
                // while($p->isRunning()) {
                //     sleep(1);
                // }

                // dd($p->isSuccessful());

                // if (!$p->isSuccessful()) {
                //     throw new \Exception($p->getErrorOutput());
                // }
            }
            Redis::hdel('calls', 'updating');

            return json_encode($data);
        } catch(\Exception $e)  {
            $data['status'] = 500;
            $data['message'] = $e->getMessage() . ' at line ' . $e->getLine();

            Redis::hdel('calls', 'updating');

            return json_encode($data);
        }
    }

    public function checkCallUpdateState() {
        try {
            return json_encode(Redis::hgetall('calls'));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function getInboundAndTransferCalls() {
        return collect(DB::select("SELECT cdrTrans.callid  from `cdr_records` as `cdrTrans`
                WHERE `callid` in (
                    SELECT `cdrTrans2`.`callid`
                    from `cdr_records` as `cdrTrans2`
                    inner join `cdr_records` as `cdrTransComp` on `cdrTrans2`.`callid` = `cdrTransComp`.`callid` AND `cdrTrans2`.`callduration` > `cdrTransComp`.`callduration`
                    WHERE `cdrTrans2`.`type` = 'Transfer'
                    AND `cdrTrans2`.`timestart` BETWEEN '{$this->carbon::now()->subMonths(12)->format($this->dateFormat)}' AND '{$this->carbon::now()->format($this->dateFormat)}'
                    AND `cdrTrans2`.`callto` not in (6501, 6502)
                    AND `cdrTrans2`.`callto` RLIKE '{$this->agentsForQuery}'
                    AND `cdrTrans2`.`status` = 'ANSWERED'
                )

                UNION DISTINCT

                SELECT cdrInb.callid from `cdr_records` as `cdrInb`
                WHERE `callid` in (
                    SELECT `cdrInb2`.`callid`
                    from `cdr_records` as `cdrInb2`
                    WHERE `cdrInb2`.`type` = 'Inbound'
                    AND `cdrInb2`.`timestart` BETWEEN '{$this->carbon::now()->subMonths(12)->format($this->dateFormat)}' AND '{$this->carbon::now()->format($this->dateFormat)}'
                    AND `cdrInb2`.`callto` not in (6501, 6502)
                    AND `cdrInb2`.`callto` RLIKE '{$this->agentsForQuery}'
                    AND `cdrInb2`.`status` = 'ANSWERED'
                )

                UNION DISTINCT

                SELECT cdrPrev.callid from `cdr_records` as `cdrPrev`
                WHERE `callid` in (
                    SELECT `cdrPrev2`.`callid`
                    from `cdr_records` as `cdrPrev2`
                    WHERE `cdrPrev2`.`type` = 'Outbound'
                    AND `cdrPrev2`.`timestart` BETWEEN '{$this->carbon::now()->subMonths(12)->format($this->dateFormat)}' AND '{$this->carbon::now()->format($this->dateFormat)}'
                    AND `cdrPrev2`.`callto` = '" . config('app.prevention_number') .  "'
                    AND `cdrPrev2`.`status` = 'ANSWERED'
                )"))
                ->pluck('callid')
                ->toArray();
    }
}
