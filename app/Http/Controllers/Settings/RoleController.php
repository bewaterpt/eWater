<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $roles = Role::all();

        return view('settings.toles.index', ['roles' => $roles]);
    }
}
