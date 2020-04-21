<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;

class SettingsController extends Controller
{
    public function change_locale(Request $request) {
        $user = Auth::user();

        if ($user) {
            $user->locale = $request->route('locale');
            $user->save();
            app()->setLocale($user->locale);
        } else {
            $request->session()->put('locale', $request->route('locale'));
            app()->setLocale($request->session()->get('locale', config('app.locale')));
        }


        return redirect()->back()->with(['success' => 'settings.locale_saved']);
    }
}
