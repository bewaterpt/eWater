<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\FailureType;

class MaterialController extends Controller
{
    public function index(Request $request) {
        return view('settings.materials.index', ['materials' => Material::all()]);
    }

    public function create(Request $request) {

        if ($request->method() === "POST") {

            $validatedData = $request->validate([
                'designation' => 'required|max:255',
                'failureType' => 'required|numeric',
            ]);

            try {
                $material = new Material();
                $material->designation = $request->designation;

                if (FailureType::exists($request->failureType)) {
                    $material->failureType()->associate($request->failureType);
                } else {
                    return redirect(route('settings.materials.list'))->withErrors(__('settings.failure_type.not_exists'), 'custom');
                }

                $material->save();

                return redirect(route('settings.materials.list'))->with(['success' => __('settings.material_created')]);
            } catch (\Exception $e) {
                return redirect(route('settings.materials.list'))->withErrors($e->getMessage(), 'custom');
            }
        } else {
            return view('settings.materials.create', ['failureTypes' => FailureType::all()]);
        }
    }

    public function delete($id) {
        $material = Material::find($id);

        if ($material) {
            $material->delete();
            return redirect()->back()->with(['success' => __('settings.materials.deleted')]);
        } else {
            return redirect()->back()->with(['success' => __('settings.materials.not_exists')]);
        }
    }
}
