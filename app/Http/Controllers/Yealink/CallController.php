<?php

namespace App\Http\Controllers\Yealink;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yealink\Pbx;
use App\Models\Delegation;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\Helper;
use Log;
use Auth;
use DB;
use Artisan;
use App\Models\Yealink\CDRRecord;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
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
    }

    public function pbxList(Request $request) {
        $pbxList = Pbx::all();

        // dd($request);

        return view('calls.pbx.index', ['pbxList' => $pbxList]);
    }

    public function create() {
        $delegations = Delegation::all();
        return view('calls.pbx.create', ['delegations' => $delegations]);
    }

    public function store(Request $request) {
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
        $helper = new Helper();
        if ($request->ajax()) {
            // dd($request->input());
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

            $cdrs = DB::table('cdr_records as cdrTrans')
                    ->select('cdrTrans.*')
                    ->join('cdr_records as cdrTransComp', function ($join) {
                        $join->on('cdrTrans.callid', '=', 'cdrTransComp.callid')->on('cdrTrans.callduration', '>', 'cdrTransComp.callduration');
                    })
                    ->where('cdrTrans.type', 'Transfer')
                    ->whereBetween('cdrTrans.timestart', [Carbon::now()->subMonths(12)->format($this->dateFormat), Carbon::now()->format($this->dateFormat)])
                    ->whereNotIn('cdrTrans.callto', [6501, 6502])
                    ->where('cdrTrans.callto', 'rlike', $this->agentsForQuery)
                    ->where('cdrTrans.status', 'ANSWERED');

            foreach ($searchCols as $searchCol) {
                $op = isset($this->operators[$searchCol['name']]) ? $this->operators[$searchCol['name']] : 'like';
                $cdrs->where('cdrTrans.'.$searchCol['name'], $op, ($op == 'like' ? '%' :'') . $searchCol['value'] . ($op == 'like' ? '%' :''));
            }

            $cdrs = DB::table('cdr_records as cdrInb')
                    ->select('cdrInb.*')
                    ->where('cdrInb.type', 'Inbound')
                    ->whereBetween('cdrInb.timestart', [Carbon::now()->subMonths(12)->format($this->dateFormat), Carbon::now()->format($this->dateFormat)])
                    ->whereNotIn('cdrInb.callto', [6501, 6502])
                    ->where('cdrInb.callto', 'rlike', $this->agentsForQuery)
                    ->where('cdrInb.status', 'ANSWERED')
                    ->union($cdrs)
                    ->orderBy($sortCol, $sortDir);




            foreach ($searchCols as $searchCol) {
                $op = isset($this->operators[$searchCol['name']]) ? $this->operators[$searchCol['name']] : 'like';
                $cdrs->where($searchCol['name'], $op, ($op == 'like' || $op == 'rlike' ? '%' :'') . $searchCol['value'] . ($op == 'like' || $op == 'rlike' ? '%' :''));
                // dd($cdrs->toSql());
            }

            // dd($cdrs2->first());
            // Set filters
            // /* Filter Placeholder */

            $testArr = $cdrs->get()->map(function ($item) {
                return $item->callid;
            })->toArray();

            $testCount = collect(array_count_values($testArr));

            $testCount = $testCount->map(function ($item) {
                return $item === 2 ? true : false;
            });

            // Get row count
            $total = $cdrs->count();

            // Set limit and offset and get rows
            $cdrs->take($limit)->skip($offset);
            $rows = $cdrs->get();

            // Add rows to array
            $data = [];
            foreach ($rows as $row) {

                $actions = '';
                // if ($this->permission_model->checkPermission($this->group_id, 'settings/roles/edit_role')) {
                //     $actions .= '<a class="btn btn-edit-element confirm-edit-ajax" href="#" data-id="'.$row->getId().'" data-title="'.$row->name.'"  title="'.trans('global.edit').'"><i class="fal fa-pencil"></i></a>';
                // }
                // if ($this->permission_model->checkPermission($this->group_id, 'settings/roles/delete_role') && $row->type !== 'agent') {
                //     $actions .= '<a class="btn btn-danger btn-delete-element confirm-delete-ajax" href="#" data-id="'.$row->getId().'" data-title="'.$row->name.'" title="'.trans('global.delete').'"><span class="hidden title">'.trans('global.delete_group').'</span><span class="hidden msg">'.trans('global.confirm_remove_role', ['item' => $row->name]).'</span><i class="fal fa-trash"></i></a>';
                // }

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
                'draw' => intval($input['draw']),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $data
            ];

            echo json_encode($output);
            die;
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

        $cdrs = DB::table('cdr_records as cdrTrans')
                ->selectRaw('month(cdrTrans.timestart) month, cdrTrans.waitduration')
                ->join('cdr_records as cdrTransComp', function ($join) {
                    $join->on('cdrTrans.callid', '=', 'cdrTransComp.callid')->on('cdrTrans.callduration', '>', 'cdrTransComp.callduration');
                })
                ->where('cdrTrans.type', 'Transfer')
                ->whereBetween('cdrTrans.timestart', [Carbon::now()->subMonths(12)->format($this->dateFormat), Carbon::now()->format($this->dateFormat)])
                ->whereNotIn('cdrTrans.callto', [6501, 6502])
                ->where('cdrTrans.callto', 'rlike', $this->agentsForQuery)
                ->where('cdrTrans.status', 'ANSWERED');

        $cdrs = DB::table('cdr_records as cdrInb')
                ->selectRaw('monthname(cdrInb.timestart) month, cdrInb.waitduration')
                ->where('cdrInb.type', 'Inbound')
                ->whereBetween('cdrInb.timestart', [Carbon::now()->subMonths(12)->format($this->dateFormat), Carbon::now()->format($this->dateFormat)])
                ->whereNotIn('cdrInb.callto', [6501, 6502])
                ->where('cdrInb.callto', 'rlike', $this->agentsForQuery)
                ->where('cdrInb.status', 'ANSWERED')
                ->union($cdrs)
                ->get();

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

    public function export($filetype = 'csv') {
        $renderer = false;

        if ($filetype == 'pdf') {
            $renderer = \Maatwebsite\Excel\Excel::MPDF;
        }
        return (new CDRRecordExport())->download(__('calls.call_records') . '.' . $filetype, ($renderer ? $renderer : null), ['X-ewater-filename' => __('calls.call_records') . '.' . $filetype]);
    }

    public function refetch() {
        $data = [
            'status' => 200,
            'message' => 'OK'
        ];

        try {
            Artisan::call('calls:get');
        } catch(\Exception $e)  {
            $data['code'] = 500;
            $data['message'] = $e->getMessage();
            return json_encode($data);
        }
    }
}
