<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyReports\Status;
use App\Models\Role;

class StatusController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {
        $statuses = Status::all();

        return view('settings.statuses.index')->with(['statuses' => $statuses]);
    }

    public function edit(Request $request, $statusId) {
        $status = Status::find($statusId);
        $statusRoleIds = $status->roles()->pluck('id');
        $roles = Role::whereNotIn('id', $statusRoleIds)->get();

        return view('settings.statuses.edit')->with(['status' => $status, 'roles' => $roles]);
    }

    public function update(Request $request, $statusId) {
        $status = Status::find($statusId);
        $status->name = $request->input('name');
        $status->save();

        $roleIds;
        if ($request->input('roles')) {
            $roleIds = explode(', ', $request->input('roles'));
        } else {
            $roleIds = [];
        }

        $status->roles()->sync($roleIds);

        return redirect(route('settings.statuses.list'));
    }
}
