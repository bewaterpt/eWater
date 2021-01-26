<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InterruptionMotive as Motive;
use App\User;
use Mail;
use Auth;
class MotivesController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Presents a list of Interruption Motives
     *
     * @param Request $request The request data
     *
     * @return \Illuminate\View\Factory View ID/Name: settings.motives.index
     */
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
            //so os admins podem ver os deleted motives
            if($this->currentUser->hasRoles(['admin'])){
                $motives = Motive::select('*')->withTrashed();
            }
            else{
                $motives = Motive::select('*');
            }


            foreach ($searchCols as $searchCol) {
                $motives->orWhere($searchCol['name'], 'rlike', $searchCol['value']);

            }

            // Get row count
            $total = $motives->count();

            // Set limit and offset and get rows
            $motives->orderBy($sortCol, $sortDir);
            $motives->take($limit)->skip($offset);
            $rows = $motives->get();

            foreach($rows as $row){
                $actions = '';

                if ($this->permissionModel->can('interruptions.motives.edit') && !$row->trashed()) {
                    if ($row->scheduled && !$this->currentUser->hasRoles(['admin'])) {

                    } else {
                        $actions .= '<a class="text-primary edit px-1" href="' . route('interruptions.motives.edit', ['id' => $row->id]) . '" title="'.trans('general.edit').'"><i class="fas fa-edit"></i></a>';
                    }
                }

                if (($this->permissionModel->can('interruptions.motives.delete') || ($this->currentUser->isAdmin())) && !$row->trashed()) {
                    $actions .= '<a class="text-danger delete px-1" href="' . route('interruptions.motives.delete', ['id' => $row->id]) . '" title="'.trans('general.delete').'"><i class="fas fa-trash-alt"></i></a>';
                }

                if ($row->trashed()) {
                    $actions = '<a class="text-primary edit px-1" href="' . route('interruptions.motives.restore', ['id' => $row->id]) . '" title="'.trans('general.restore').'"><i class="fas fa-redo"></i></a>';
                }

                $type = '';

                if ($row->scheduled) {
                    $type = '<h5><i class="far fa-calendar-alt" title="' . __('general.interruptions.scheduled') .'"></i></h5>';
                } else {
                    $type = '<h5><i class="far fa-calendar-times" title="' . __('general.interruptions.unscheduled') . '"></i></h5>';
                }


                $data[] = [
                    'actions' => $actions,
                    'name' => $row->name,
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

            return json_encode($output);

        }

        return view('settings.motives.index');
    }

    /**
     * Presents the motive creation page
     *
     * @return \Illuminate\View\Factory View ID/Name: settings.motives.create
     */
    public function create() {
        $type = '';
        $scheduled = false;
        $user = User::all();

        if (
            $this->currentUser->countRoles(['ewater_interrupcoes_programadas_criacao', 'ewater_interrupcoes_programadas_edicao']) > 0 &&
            $this->currentUser->hasRoles(['ewater_interrupcoes_nao_programadas']) === false
        ) {
            $type = mb_strtolower(__('general.interruptions.is_scheduled'));
            $scheduled = true;
        } else if (
            $this->currentUser->hasRoles(['ewater_interrupcoes_nao_programadas']) &&
            $this->currentUser->countRoles(['ewater_interrupcoes_programadas_criacao', 'ewater_interrupcoes_programadas_edicao']) == 0
        ) {
            $type = mb_strtolower(__('general.interruptions.is_unscheduled'));
        }

        return view('settings.motives.create', ['type' => $type, 'scheduled' => $scheduled]);
    }
    /**
     * Stores the motive in the database
     *
     * @param Request $request The request data
     *
     * @return \Illuminate\Http\Client\Response Redirects user to motive list
     */
    public function store(Request $request) {
        $user = Auth::user();

        $scheduled = $request->scheduled == 'true' ? true : false;
        $motive = new Motive();
        $motive->name = $request->name;
        $motive->slug = $this->helper->transliterate($request->name, 1);
        $motive->scheduled = $scheduled;
        $motive->created_by = $user->id;
        $motive->save();

        return redirect(route('interruptions.motives.list'))->with('success');
    }

    /**
     * Presents the motive edit page
     *
     * @return \Illuminate\View\Factory View ID/Name: settings.motives.edit
     */
    public function edit(Request $request, $id) {

        $motive = Motive::find($id);

        $url = url()->previous();
        $route = app('router')->getRoutes($url)->match(app('request')->create($url))->getName();
        $this->session->put('previous-rt', $route);

        return view('settings.motives.edit', ['motive' => $motive]);
    }
    /***
     * Função para apagar row na base de dados
     */
    public function delete($id) {
        if(!$this->permissionModel->can('interruptions.motives.delete')){
            return redirect()->back()->withErrors(__('auth.permission_denied', ['route' => route('interruptions.motives.delete')]), 'custom');
        }

        Motive::find($id)->delete();

        return redirect()->back()->with('success');
    }
    /**
     * Restaurar as rows com softDelete
     */
    public function restore($id) {
        if(!$this->permissionModel->can('interruptions.motives.restore')){
            return redirect()->back()->withErrors(__('auth.permission_denied', ['route' => route('interruptions.motives.restore')]), 'custom');
        }

        Motive::withTrashed()->find($id)->restore();

        return redirect()->back()->with('success');
    }


    /**
     * Updates a motive in the database
     *
     * @param Request $request The request data
     *
     * @return \Illuminate\Http\Client\Response Redirects user to motive list
     */
    public function update(Request $request, $id) {
        $user = Auth::user();

        $motive = Motive::find($id);
        $motive->name = $request->name;
        $motive->slug = $request->slug;
        $motive->updatedBy()->associate($user->id);
        $motive->updated_at = $request->updated_at;

        $motive->save();

        return redirect(route('interruptions.motives.list'))->with('success');
    }
}
