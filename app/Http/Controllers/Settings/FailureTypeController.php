<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FailureType;

class FailureTypeController extends Controller
{
    public function index(Request $request) {
        return view('settings.failure_types.index', ['failureTypes' => FailureType::all()]);
    }

    public function create(Request $request) {

        if ($request->method() === "POST") {

            try {
                $failureType = new FailureType();
                $failureType->designation = $request->designation;
                $failureType->save();

                return redirect(route('settings.failure_types.index'))->with(['success' => __('settings.failure_type_created')]);
            } catch (\Exception $e) {
                return  redirect(route('settings.failure_types.index'))->withErrors($e->getMessage(), 'custom');
            }
        } else {
            return view('settings.failure_types.create');
        }
    }

    public function toggle_state($failureTypeId) {
        $failureType = FailureType::find($failureTypeId);

        if (!$failureType) {
            return redirect()->back()->withErrors(__('settings.user_doesnt_exist'), 'custom');
        }

        if ($failureType->enabled()) {
            $failureType->disable();
        } else {
            $failureType->enable();
        }

        return redirect()->back()->with('success');
    }

    public function delete() {

    }
}
