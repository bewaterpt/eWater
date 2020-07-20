<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use App\Models\Permission;
use App\Helpers\Helper;
use App;
use DB;
use Auth;
use View;
use Route;
use App\Models\DailyReports\Status;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;
    protected $userRoles;
    protected $permissionModel;
    protected $statusModel;
    protected $isLoggedIn = false;
    protected $helper;

    public function __construct() {
        $this->middleware(function($request, $next) {
            $this->permissionModel = new Permission;
            $this->statusModel = new Status;
            $this->helper = new Helper;

            View::share('pmodel', $this->permissionModel);
            View::share('helpers', $this->helper);
            View::share('currentUser', Auth::user());
            View::share('carbon', new Carbon());

            return $next($request);
        });
    }

    /**
     * @method encodeId
     *
     * @param Integer $id - The identifier to be encoded
     * @param Boolean $alt = false - Connection switch
     *
     * @link PROJECT_DIR/config/hashids -> connections
     */
    public function encodeId($id, $alt = false) {
        return $alt ? \Hashids::connection('alt')->encode($id) : \Hashids::encode($id);
    }

    public function decodeId($id, $alt = false) {
        return $alt ? \Hashids::connection('alt')->decode($id) : \Hashids::decode($id);
    }
}
