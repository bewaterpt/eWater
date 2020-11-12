<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Interruption;
use App\Models\Connectors\OutonoInterrupcoesProg;
use App\Models\Connectors\OutonoInterrupcoes;
use App\Models\Delegation;
use Auth;
use Illuminate\Support\Facades\Artisan;

class InterruptionController extends Controller
{
    public function __construct() {
        parent::__construct();
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

            $interruptions = Interruption::select('*');
            if (!$this->currentUser->isAdmin() && $this->currentUser->delegation()->exists()) {
                $interruptions->where('delegation_id', $this->currentUser->delegation()->first()->id);
            }

            if ($this->currentUser->isAdmin()) {
                $interruptions->withTrashed();
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
            $data = $this->buildData($rows);

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
    }

    public function unscheduled(Request $request) {

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

            $interruptions = Interruption::select('*')->where('scheduled', false);
            if (!$this->currentUser->isAdmin() && $this->currentUser->delegation()->exists()) {
                $interruptions->where('delegation_id', $this->currentUser->delegation()->first()->id);
            } else {
                $interruptions->withTrashed();
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
            $data = $this->buildData($rows);

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
    }

    public function scheduled(Request $request) {

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
                } elseif ($value['searchable'] === 'true' && $input['search']['value']) {
                    $searchCols[] = [
                        'name' => $value['name'],
                        'value' => $input['search']['value']
                    ];
                }
            }

            $interruptions = Interruption::select('*')->where('scheduled', true);
            if (!$this->currentUser->isAdmin() && $this->currentUser->delegation()->exists()) {
                $interruptions->where('delegation_id', $this->currentUser->delegation()->first()->id);
            } else {
                $interruptions->withTrashed();
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
            $data = $this->buildData($rows);

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
    }

    protected function buildData($rows) {
        $data = [];
        foreach ($rows as $row) {
            $actions = '';
            // if ($this->permissionModel->can('interruptions.')) {
            //     $actions .= '<a class="text-info edit mr-1" href="' . route('daily_reports.view', ['id' => $row->id]) . '" title="'.trans('general.view').'"><i class="fas fa-eye"></i></a>';
            // }
            if ($this->permissionModel->can('interruptions.edit') && !$row->trashed()) {
                $actions .= '<a class="text-primary edit px-1" href="' . route('interruptions.edit', ['id' => $row->id]) . '" title="'.trans('general.edit').'"><i class="fas fa-edit"></i></a>';
            }

            if ((($this->permissionModel->can('interruptions.delete') && !$row->synced && $row->outono_id != null) || ($this->currentUser->isAdmin())) && !$row->trashed()) {
                $actions .= '<a class="text-danger delete px-1" href="' . route('interruptions.delete', ['id' => $row->id]) . '" title="'.trans('general.delete').'"><i class="fas fa-trash-alt"></i></a>';
            }

            if ($this->permissionModel->can('interruptions.restore') && $row->trashed()) {
                $actions .= '<a class="text-danger restore px-1" href="' . route('interruptions.restore', ['id' => $row->id]) . '" title="'.trans('general.restore').'"><i class="fas fa-trash-restore-alt"></i></a>';
            }

            $type = '';

            if ($row->scheduled) {
                $type = '<h5><i class="far fa-calendar-alt" title="' . __('general.interruptions.scheduled') .'"></i></h5>';
            } else {
                $type = '<h5><i class="far fa-calendar-times" title="' . __('general.interruptions.unscheduled') . '"></i></h5>';
            }

            $data[] = [
                'actions' => $actions,
                'work_id' => $row->work_id,
                'start_date' => $row->start_date,
                'affected_area' => $row->affected_area,
                'reinstatement_date' => $row->reinstatement_date,
                // 'coordinates' => $row->coordinates ? $row->coordinates : 'N/A',
                'scheduled' => $type,
                'outono_id' => $row->outono_id
            ];
        }

        return $data;
    }

    public function create() {
        $delegations = Delegation::all();

        $url = url()->previous();
        $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();
        $this->session->put('previous-rt', $route);

        return view('interruptions.create', ['delegations' => $delegations]);
    }

    public function store(Request $request) {
        $user = Auth::user();

        $scheduled = $request->scheduled == 'on' ? true : false;

        $interruption = new Interruption();
        $interruption->work_id = $request->work_id;
        $interruption->start_date = $request->start_date;
        $interruption->reinstatement_date = $request->reinstatement_date;
        $interruption->delegation()->associate($request->delegation);
        $interruption->user()->associate($user->id);
        $interruption->scheduled = $scheduled;
        $interruption->affected_area = $request->affected_area;

        $outonoInterruption = $scheduled ? new OutonoInterrupcoesProg() : new OutonoInterrupcoes();
        $outonoInterruption->numObra = $request->work_id;
        $outonoInterruption->dtInicio = Carbon::parse($request->start_date)->format('Y-m-d H:i:s.v');
        $outonoInterruption->dtRestabelecimento = Carbon::parse($request->reinstatement_date)->format('Y-m-d H:i:s.v');
        $outonoInterruption->areaAfectada = strip_tags($request->affected_area);
        $outonoInterruption->save();

        $interruption->outono_id = $outonoInterruption->{$outonoInterruption->getKeyName()};
        $interruption->synced = true;
        $interruption->save();

        Artisan::call('interruptions:export');

        return redirect(route($this->session->get('previous-rt')))->with('success');
    }

    public function edit($id) {
        $delegations = Delegation::all();
        $url = url()->previous();
        $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();
        $this->session->put('previous-rt', $route);

        return view('interruptions.edit', ['delegations' => $delegations, 'interruption' => Interruption::find($id)]);
    }

    public function delete($id) {
        $interruption = Interruption::find($id);

        if ($interruption->synced && $interruption->outono_id != null) {
            $outonoInterruption = $interruption->scheduled ? OutonoInterrupcoesProg::find($interruption->outono_id) : OutonoInterrupcoes::find($interruption->outono_id);
            if ($outonoInterruption) {
                $outonoInterruption->delete();
            }
        }

        $interruption->delete();

        Artisan::call('interruptions:export');

        return redirect()->back()->with('success');
    }

    public function restore($id) {
        $interruption = Interruption::withTrashed()->find($id);
        $outonoInterruption = $interruption->scheduled ? new OutonoInterrupcoesProg() : new OutonoInterrupcoes();

        if ($interruption->synced) {
            $outonoInterruption->numObra = $interruption->work_id;
            $outonoInterruption->dtInicio = Carbon::parse($interruption->start_date)->format('Y-m-d H:i:s.v');
            $outonoInterruption->dtRestabelecimento = Carbon::parse($interruption->reinstatement_date)->format('Y-m-d H:i:s.v');
            $outonoInterruption->areaAfectada = strip_tags($interruption->affected_area);
            $outonoInterruption->save();
        }

        $interruption->restore();
        $interruption->outono_id = $outonoInterruption->{$outonoInterruption->getKeyName()};
        $interruption->save();

        Artisan::call('interruptions:export');

        return redirect()->back()->with('success');
    }

    public function update(Request $request, $id) {
        $user = Auth::user();

        $interruption = Interruption::find($id);
        $interruption->work_id = $request->work_id;
        $interruption->start_date = $request->start_date;
        $interruption->reinstatement_date = $request->reinstatement_date;
        $interruption->delegation()->associate($request->delegation);
        $interruption->user()->associate($user->id);
        $interruption->updatedBy()->associate($user->id);
        $interruption->affected_area = $request->affected_area;

        $outonoInterruption = $interruption->scheduled ? OutonoInterrupcoesProg::find($interruption->outono_id) : OutonoInterrupcoes::find($interruption->outono_id);
        $outonoInterruption->numObra = $request->work_id;
        $outonoInterruption->dtInicio = Carbon::parse($request->start_date)->format('Y-m-d H:i:s.v');
        $outonoInterruption->dtRestabelecimento = Carbon::parse($request->reinstatement_date)->format('Y-m-d H:i:s.v');
        $outonoInterruption->areaAfectada = strip_tags($request->affected_area);
        $outonoInterruption->save();


        $interruption->synced = true;
        $interruption->save();

        Artisan::call('interruptions:export');

        return redirect(route($this->session->get('previous-rt')))->with('success');
    }
}
