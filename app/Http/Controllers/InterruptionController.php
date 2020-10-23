<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interruption;
use App\Models\Delegation;
use Auth;

class InterruptionController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {
        // $interruptions = Interruption::all();
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

            $interruptions = Interruption::select('*');
            if (!$user->isAdmin() && $user->delegation()->exists()) {
                $interruptions->where('delegation_id', $user->delegation()->first()->id);
            }

            foreach ($searchCols as $searchCol) {
                $interruptions->orWhere($searchCol['name'], 'rlike', $searchCol['value']);
            }

            // Get row count
            $total = $interruptions->count();

            // Set filters
            // /* Filter Placeholder */

            // Set limit and offset and get rows
            $interruptions->orderBy($sortCol, $sortDir);
            $interruptions->take($limit)->skip($offset);
            $rows = $interruptions->get();


            // Add rows to array
            $data = [];
            foreach ($rows as $row) {

                $actions = '';
                // if ($this->permissionModel->can('interruptions.')) {
                //     $actions .= '<a class="text-info edit mr-1" href="' . route('daily_reports.view', ['id' => $row->id]) . '" title="'.trans('general.view').'"><i class="fas fa-eye"></i></a>';
                // }
                if ($this->permissionModel->can('interruptions.edit') && $row->scheduled) {
                    $actions .= '<a class="text-info edit" href="' . route('interruptions.edit', ['id' => $row->id]) . '" title="'.trans('general.edit').'"><i class="fas fa-edit"></i></a>';
                }

                $type = '';

                if ($row->scheduled) {
                    $type = '<h5><i class="far fa-calendar-check text-success" title="' . __('general.interruptions.scheduled') .'"></i></h5>';
                } else {
                    $type = '<h5><i class="far fa-calendar-times text-danger" title="' . __('general.interruptions.unscheduled') . '"></i></h5>';
                }

                $data[] = [
                    'actions' => $actions,
                    'work_id' => $row->work_id,
                    'start_date' => $row->start_date,
                    'affected_area' => $row->affected_area,
                    'reinstatement_date' => $row->reinstatement_date,
                    // 'coordinates' => $row->coordinates ? $row->coordinates : 'N/A',
                    'scheduled' => $type,
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

        return view('interruptions.index');

        return view('interruptions.index', ['interruptions' => $interruptions]);
    }

    public function create() {
        $delegations = Delegation::all();

        return view('interruptions.create', ['delegations' => $delegations]);
    }

    public function store(Request $request) {
        $user = Auth::user();

        $interruption = new Interruption();
        $interuption->work_id = $request->work_id;
        $interruption->start_date = $request->start_date;
        $interruption->reinstatement_date = $request->reinstatement_date;
        $interruption->delegation()->associate($request->delegation);
        $interruption->user()->associate($user->id);
        $interruption->scheduled = $request->scheduled == 'on' ? true : false;
        $interruption->affected_area = $request->affected_area;
        $interruption->save();

        return redirect()->back()->with('success');
    }
}
