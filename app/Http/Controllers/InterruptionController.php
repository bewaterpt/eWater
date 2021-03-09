<?php

namespace App\Http\Controllers;

use App\Mail\InterruptionCreated;
use App\Mail\InterruptionCanceled;
use App\Mail\InterruptionUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Interruption;
use App\Models\Connectors\OutonoInterrupcoesProg;
use App\Models\Connectors\OutonoInterrupcoes;
use App\Models\Delegation;
use Auth;
use Illuminate\Support\Facades\Artisan;
use Mail;
use App\Models\InterruptionMotive as Motive;

class InterruptionController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @method index(Request $request)
     *
     * Serves the view that lists interruptions and loads the interruptions datatable data structures
     *
     * @param Illuminate\Http\Request $request Request data
     *
     * @return \Illuminate\View\Factory View ID/Name: interruptions.index
     */
    public function index(Request $request)
    {

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

            $interruptions = $this->getBaseQuery();

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

        // $interruptionsDB = Interruption::orderBy('id','desc')->first();
        // dd($interruptionsDB);

        return view('interruptions.index');
    }

    /**
     * @method unscheduled(Request $request)
     *
     * Serves the view that lists unscheduled interruptions and loads the interruptions datatable data structures
     *
     * @param Illuminate\Http\Request $request Request data
     *
     * @return \Illuminate\View\Factory View ID/Name: interruptions.index
     */
    public function unscheduled(Request $request)
    {

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

            $interruptions = $this->getBaseQuery(false);
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

    /**
     * @method scheduled(Request $request)
     *
     * Serves the view that lists scheduled interruptions and loads the interruptions datatable data structures
     *
     * @param Illuminate\Http\Request $request Request data
     *
     * @return \Illuminate\View\Factory View ID/Name: interruptions.index
     */
    public function scheduled(Request $request)
    {

        if (!$this->currentUser->hasRoles(['ewater_interrupcoes_programadas_criacao', 'admin', 'ewater_interrupcoes_programadas_edicao'])) {
            return redirect()->back()->withErrors(__('auth.permission_denied', ['route' => $request->path()]), 'custom');
        }

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

            // Set filters
            $interruptions = $this->getBaseQuery(true);
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

            return json_encode($output);
        }

        return view('interruptions.index');
    }

    /**
     * Returns a prefiltered Eloquent ORM Query Builder instance for further filtering data
     *
     * @param boolean|mixed $scheduled
     *
     * @return Illuminate\Database\Eloquent\Builder Filtered instance of the Eloquent ORM Query Builder
     *
     * @link dawda
     */
    private function getBaseQuery($scheduled = null)
    {

        $int = Interruption::select('*');

        if ($scheduled === false) {
            $int->where('scheduled', false);
        } else if ($scheduled === true) {
            $int->where('scheduled', true);
        }

        if (!$this->currentUser->isAdmin() && $this->currentUser->delegation()->exists()) {
            $int->where('delegation_id', $this->currentUser->delegation()->first()->id);
        }

        if ($this->currentUser->isAdmin() || $this->permissionModel->can('interruptions.delete')) {
            $int->withTrashed();
        }

        return $int;
    }

    private function buildData($rows)
    {
        $data = [];

        // dd($rows);
        foreach ($rows as $row) {
            $actions = '';
            // if ($this->permissionModel->can('interruptions.')) {
            //     $actions .= '<a class="text-info edit mr-1" href="' . route('daily_reports.view', ['id' => $row->id]) . '" title="'.trans('general.view').'"><i class="fas fa-eye"></i></a>';
            // }
            // dd($row);
            if ($this->permissionModel->can('interruptions.edit') && !$row->trashed()) {
                if ($row->scheduled && !$this->currentUser->hasRoles(['ewater_interrupcoes_programadas_criacao', 'admin', 'ewater_interrupcoes_programadas_edicao'])) {
                } else {
                    $actions .= '<a class="text-primary edit px-1" href="' . route('interruptions.edit', ['id' => $row->id]) . '" title="' . trans('general.edit') . '"><i class="fas fa-edit"></i></a>';
                }
            }

            if (($this->permissionModel->can('interruptions.delete') || ($this->currentUser->isAdmin())) && !$row->trashed()) {
                $actions .= '<a class="text-danger delete px-1" href="' . route('interruptions.delete', ['id' => $row->id]) . '" title="' . trans('general.delete') . '"><i class="fas fa-trash-alt"></i></a>';
            }

            if ($row->trashed()) {
                $actions = "<span>" . __('general.interruptions.canceled') . "</span>";
            }

            // if ($this->permissionModel->can('interruptions.restore') && $row->trashed()) {
            //     $actions .= '<a class="text-danger restore px-1" href="' . route('interruptions.restore', ['id' => $row->id]) . '" title="'.trans('general.restore').'"><i class="fas fa-trash-restore-alt"></i></a>';
            // }

            $type = '';

            if ($row->scheduled) {
                $type = '<h5><i class="far fa-calendar-alt" title="' . __('general.interruptions.scheduled') . '"></i></h5>';
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
                'outono_id' => $row->outono_id,
                'trashed' => $row->trashed(),
            ];
        }

        return $data;
    }

    public function create()
    {
        $delegations = Delegation::all();
        $motives = Motive::unscheduled();

        $type = '';
        $scheduled = false;

        if (
            $this->currentUser->countRoles(['ewater_interrupcoes_programadas_criacao', 'ewater_interrupcoes_programadas_edicao']) > 0 &&
            $this->currentUser->hasRoles(['ewater_interrupcoes_nao_programadas']) === false
        ) {
            $type = mb_strtolower(__('general.interruptions.is_scheduled'));
            $scheduled = true;
            $motives = Motive::scheduled();
        } else if (
            $this->currentUser->hasRoles(['ewater_interrupcoes_nao_programadas']) &&
            $this->currentUser->countRoles(['ewater_interrupcoes_programadas_criacao', 'ewater_interrupcoes_programadas_edicao']) == 0
        ) {
            $type = mb_strtolower(__('general.interruptions.is_unscheduled'));
            $motives = Motive::unscheduled();
        }

        return view('interruptions.create', ['delegations' => $delegations, 'type' => $type, 'scheduled' => $scheduled, 'motives' => $motives]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $this->validate($request, [
            'affected_area' => ['required']
        ]);

        $scheduled = $request->scheduled == 'true' ? true : false;

        $interruption = new Interruption();
        $interruption->work_id = $request->work_id;
        $interruption->start_date = $request->start_date;
        $interruption->reinstatement_date = $request->reinstatement_date;
        $interruption->delegation()->associate($request->delegation);
        $interruption->user()->associate($user->id);
        $interruption->scheduled = $scheduled;
        $interruption->affected_area = $request->affected_area;
        $interruption->motive()->associate($request->motive);

        if (config('app.env') === 'prod') {
            $outonoInterruption = $scheduled ? new OutonoInterrupcoesProg() : new OutonoInterrupcoes();
            $outonoInterruption->numObra = $request->work_id;
            $outonoInterruption->dtInicio = Carbon::parse($request->start_date)->format('Y-m-d H:i:s.v');
            $outonoInterruption->dtRestabelecimento = Carbon::parse($request->reinstatement_date)->format('Y-m-d H:i:s.v');
            $outonoInterruption->areaAfectada = strip_tags($request->affected_area);
            $outonoInterruption->save();
            $interruption->outono_id = $outonoInterruption->{$outonoInterruption->getKeyName()};
        }

        $interruption->synced = true;
        $interruption->save();


        //if ($interruption->scheduled) {
        try {
            Mail::to(config('app.emails.developer'))->send(new InterruptionCreated($interruption));
        } catch (\Exception $e) {
        }
        //}


        Artisan::call('interruptions:export');

        return redirect(route('interruptions.list'))->with('success');
    }

    public function view(Request $request, $id)
    {
        $interruption = Interruption::where('id', $id);

        if ($this->currentUser->isAdmin() || $this->permissionModel->can('interruptions.delete')) {
            $interruption->withTrashed();
        }

        return view('interruptions.view', ['interruption' => $interruption->first()]);
    }

    public function edit(Request $request, $id)
    {
        $int = Interruption::find($id);
        $motives = Motive::where('scheduled', $int->scheduled)->get();

        if ($int->scheduled && !$this->currentUser->hasRoles(['ewater_interrupcoes_programadas_edicao', 'admin'])) {
            return redirect()->back()->withErrors(__('auth.permission_denied', ['route' => $request->path()]), 'custom');
        }

        $delegations = Delegation::all();
        $url = url()->previous();
        $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();
        $this->session->put('previous-rt', $route);

        return view('interruptions.edit', ['delegations' => $delegations, 'interruption' => $int, 'motives' => $motives]);
    }

    public function delete($id)
    {
        $interruption = Interruption::find($id);

        if ($interruption->synced && $interruption->outono_id != null) {
            $outonoInterruption = $interruption->scheduled ? OutonoInterrupcoesProg::find($interruption->outono_id) : OutonoInterrupcoes::find($interruption->outono_id);
            if ($outonoInterruption) {
                $outonoInterruption->delete();
            }
        }

        $interruption->delete();

        if ($interruption->scheduled) {
        try {
            Mail::to(config('app.emails.interruptions_ao'))->send(new InterruptionCanceled($interruption));
        } catch (\Exception $e) {
        }
        }

        Artisan::call('interruptions:export');

        return redirect()->back()->with('success');
    }

    /**
     * @method restore
     *
     * Restores an interruption
     *
     * @deprecated
     */
    public function restore($id)
    {
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

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $interruption = Interruption::find($id);
        $prevInt = clone ($interruption);
        $interruption->work_id = $request->work_id;
        $interruption->start_date = $request->start_date;
        $interruption->reinstatement_date = $request->reinstatement_date;
        $interruption->delegation()->associate($request->delegation);
        $interruption->user()->associate($user->id);
        $interruption->updatedBy()->associate($user->id);
        $interruption->affected_area = $request->affected_area;
        $interruption->motive()->associate($request->motive);

        if ($interruption->outono_id && config('app.env') === 'prod') {
            $outonoInterruption = $interruption->scheduled ? OutonoInterrupcoesProg::find($interruption->outono_id) : OutonoInterrupcoes::find($interruption->outono_id);

            if ($outonoInterruption) {
                $outonoInterruption->numObra = $request->work_id;
                $outonoInterruption->dtInicio = Carbon::parse($request->start_date)->format('Y-m-d H:i:s.v');
                $outonoInterruption->dtRestabelecimento = Carbon::parse($request->reinstatement_date)->format('Y-m-d H:i:s.v');
                $outonoInterruption->areaAfectada = strip_tags($request->affected_area);
                $outonoInterruption->save();
            }
        }


        $interruption->synced = true;
        $interruption->save();

        // if(env('APP_ENV') === 'prod' ){
        //     try {
        //         Mail::to(config('app.emails.interruptions_ao'))->send(new InterruptionCreated($interruption));
        //     } catch (\Exception $e) {
        //     }
        // }else{
        //     try {
        //         Mail::to(config('app.emails.interruptions_dev'))->send(new InterruptionCreated($interruption));
        //     } catch (\Exception $e) {
        //     }
        // }

        try {
            env('APP_ENV') == 'prod' ? Mail::to(config('app.emails.interruptions_ao')) : Mail::to(config('app.emails.interruptions_dev'));
        }catch (\Exception $e) {
            //@Todo Proper exception handling
        }

        Artisan::call('interruptions:export');

        return redirect(route('interruptions.list'))->with('success');
    }

    public function getMotiveList(Request $request)
    {
        $data = [
            'status' => 500,
            'message' => __('errors.unexpected_error')
        ];
        if ($request->scheduled === 'true') {
            $data['message'] = 'OK';
            $data['motives'] = Motive::scheduled();
            $data['status'] = 200;
        } else {
            $data['message'] = 'OK';
            $data['motives'] = Motive::unscheduled();
            $data['status'] = 200;
        }

        return json_encode($data);
    }
}
