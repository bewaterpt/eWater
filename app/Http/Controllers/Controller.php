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

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;
    protected $user_roles;
    protected $permission;

    public function __contruct(Request $request, Closure $next) {

        $this->user = Auth::user();
        $this->user_roles = $this->user->roles;
        $this->permission = new Permission();

        return $next($request);
    }
}
