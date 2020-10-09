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
use App\Models\Yealink\CDRRecord;
use Illuminate\Support\Carbon;

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

            $model = new CDRRecord(); // get model
            $rows = $model::orderBy($sortCol, $sortDir); // set order

            // Set filters
            /* Filter Placeholder */

            foreach ($searchCols as $searchCol) {
                $rows->orWhere($searchCol['name'], 'like', '%' . $searchCol['value'] . '%');
            }

            // Get row count
            $total = $rows->count();

            // Set limit and offset and get rows
            $rows->take($limit)->skip($offset);
            $rows = $rows->get();

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
                    'callfrom' => $row->callfrom,
                    'callto' => $row->callto,
                    'timestart' => $row->timestart,
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

        $dateFormat = 'Y-m-d H:i:s';
        $waitDurations = [];

        $cdrTransfers = collect(DB::select(DB::raw(
            "select monthname(cdrs.timestart) month, cdrs.callid, cdrs.callto, cdrs.waitduration
            from ewater.cdr_records as cdrs
            where type = 'transfer'
            and callto rlike " . $this->agentsForQuery . "
             and callduration = (
                select max(callduration) from ewater.cdr_records where callid = cdrs.callid and type = 'transfer'
            );"
        )));

        $cdrTransfers->map(function ($item) use (&$waitDurations, &$transferWaitDurations){
            $waitDurations[$item->month][] = $item->waitduration;
        });

        // dd($waitDurations);

        $cdrTransfersCallIds = $cdrTransfers->map(function ($item) {
            return $item->callid;
        })->toArray();

        $cdrInbound = CDRRecord::selectRaw('monthname(timestart) month, waitduration')
                    ->whereBetween('timestart', [Carbon::now()->startOfYear()->format($dateFormat), Carbon::now()->endOfYear()->format($dateFormat)])
                    ->whereNotIn('callto', [6501, 6502])
                    ->whereNotIn('callid', $cdrTransfersCallIds)
                    ->where('type', 'inbound')
                    ->where('callto', 'rlike', $this->agentsForQuery)
                    ->where('status', 'ANSWERED')
                    ->get();
                    // ->toSql();

        // dd($this->agentsForQuery);

        $cdrInbound->map(function ($item) use (&$waitDurations, &$inboundWaitDurations){
            $waitDurations[$item->month][] = $item->waitduration;
        });

        $waitDurations = collect($waitDurations);

        $months = CDRRecord::selectRaw('monthname(timestart) month')
                    ->distinct('month')
                    ->whereBetween('timestart', [Carbon::now()->subMonths(12)->format($dateFormat), Carbon::now()->format($dateFormat)])
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
}
