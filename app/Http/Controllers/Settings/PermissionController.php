<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Route;
use URL;

class PermissionController extends Controller
{

    public function __construct() {
        parent::__construct();
    }

    public function index(Request $request) {

        // Get all the available roles
        $roles = Role::all();

        $roleData = [];
        $rolesHeader = [__('global.permission')];

        $availableRoutes = [];

        $routeCollection = Permission::all()->pluck('route');

        foreach($roles as $role) {
            $routes = [];
            foreach ($routeCollection as $route) {

                $routeElements = explode('.', $route);
                $topRouteIndex = "";


                for ($i=0;$i<(Sizeof($routeElements)-1);$i++) {
                    $topRouteIndex .= $routeElements[$i];

                    if ($i  !== (Sizeof($routeElements)-2)) {
                        $topRouteIndex .= '.';
                    }
                }

                $routes[$topRouteIndex][] = [
                    'checked' => $this->permissionModel->roleCanAccess($role, $route),
                    'route' => $route
                ];
            }

            // dd($routes);

            $rolesHeader[] = $role->name;

            $roleData[] = [
                'role' => $role->id,
                'permissions' => $routes
            ];
        }

        $categorizedRoutes = [];
        foreach($routeCollection as $route) {

            $routeElements = explode('.', $route);
            $topRouteIndex = "";

            for ($i=0;$i<(Sizeof($routeElements)-1);$i++) {
                $topRouteIndex .= $routeElements[$i];

                if ($i  !== (Sizeof($routeElements)-2)) {
                    $topRouteIndex .= '.';
                }
            }

            $categorizedRoutes[$topRouteIndex][] = end($routeElements);
        }

        return view('settings.permissions.index', [
            'roleData' => $roleData,
            'rolesHeader' => $rolesHeader,
            'categorizedRoutes' => $this->helper->sortArray($categorizedRoutes)
        ]);
    }

    public function update(Request $request){
                $response = [
                    'status' => 500,
                    'message' => __('global.unexpected_error')
                ];

                $elementsToUpdate = [];

                if($request->json('data')){
                    $data = $request->json('data');

                    foreach($data as $element) {
                        $elementsToUpdate[$element['id']][] = $element['route'];
                    }
                }


                foreach( $elementsToUpdate as $roleId => $routes ){
                    $role = Role::find($roleId);
                    $permissionIds = Permission::whereIn('route', $routes)->pluck('id');
                    try {
                        $role->permissions()->sync($permissionIds);
                        $response['status'] = 200;
                        $response['message'] = trans('global.saved_successfully');
                    } catch (\Exception $e) {
                        $response['message'] = $e->getMessage();
                    }
                }

                return $response;
            }
}
