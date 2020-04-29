<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Permission;
use App;
use DB;
use Auth;
use View;
use Route;
use vinkla\Hashids\Facades\Hashids;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;
    protected $user_roles;
    protected $permission_model;
    protected $is_logged_in = false;

    public function __construct() {
        $this->middleware(function($request, $next) {
            $this->permission_model = new Permission;

            return $next($request);
        });
    }

    public function encodeId($id, $alt = false) {
        return $alt ? Hashids::connection('alt')->encode($id) : Hashids::encode($id);
    }

    public function decodeId($id, $alt = false) {
        return $alt ? Hashids::connection('alt')->decode($id) : Hashids::decode($id);
    }
}
