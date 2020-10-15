<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Permission;
use App\Helpers\Helper;
use App;
use DB;
use Auth;
use View;
use Route;
use App\Models\DailyReports\Status;
use App\Models\Connectors\OutonoObras;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;
    protected $userRoles;
    protected $permissionModel;
    protected $statusModel;
    protected $isLoggedIn = false;
    protected $helper;
    protected $impersonationManager;

    public function __construct() {
        $this->middleware(function($request, $next) {
            $this->permissionModel = new Permission;
            $this->statusModel = new Status;
            $this->helper = new Helper;
            $this->impersonationManager = app('impersonate');
            $isImpersonating = $this->impersonationManager->isImpersonating();
            $impersonator = false;

            if ($isImpersonating) {
                $impersonator = User::find($this->impersonationManager->getImpersonatorId());
            }

            View::share('pmodel', $this->permissionModel);
            View::share('helpers', $this->helper);
            View::share('currentUser', Auth::user());
            View::share('carbon', new Carbon());
            View::share('isImpersonating', $isImpersonating);
            View::share('impersonator', $impersonator);

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

    public function workExists(Request $request) {
        return json_encode(OutonoObras::exists($request->json('id')));
    }
}
