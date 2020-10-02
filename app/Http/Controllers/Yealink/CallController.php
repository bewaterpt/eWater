<?php

namespace App\Http\Controllers\Yealink;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yealink\Pbx;
use App\Models\Delegation;
use Illuminate\Support\Facades\Crypt;
use Log;
use Auth;
use DB;
use App\Models\Yealink\CDRRecord;

class CallController extends Controller
{

    public function __construct() {
        parent::__construct();
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
                // dd($row);
                $actions = '';
                // if ($this->permission_model->checkPermission($this->group_id, 'settings/roles/edit_role')) {
                //     $actions .= '<a class="btn btn-edit-element confirm-edit-ajax" href="#" data-id="'.$row->getId().'" data-title="'.$row->name.'"  title="'.trans('global.edit').'"><i class="fal fa-pencil"></i></a>';
                // }
                // if ($this->permission_model->checkPermission($this->group_id, 'settings/roles/delete_role') && $row->type !== 'agent') {
                //     $actions .= '<a class="btn btn-danger btn-delete-element confirm-delete-ajax" href="#" data-id="'.$row->getId().'" data-title="'.$row->name.'" title="'.trans('global.delete').'"><span class="hidden title">'.trans('global.delete_group').'</span><span class="hidden msg">'.trans('global.confirm_remove_role', ['item' => $row->name]).'</span><i class="fal fa-trash"></i></a>';
                // }

                $data[] = [
                    'actions' => $actions,
                    'callid' => $row->callid,
                    'callduration' => $row->callduration,
                    'talkduration' => $row->talkduration,
                    'waitduration' => $row->waitduration,
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
}
