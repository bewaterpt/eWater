<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use Route;

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

                $routes[$routeElements[0].'.'.$routeElements[1]][] = [
                    'checked' => $this->permission_model->roleCanAccess($role, $route),
                    'route' => $route
                ];
            }
            $rolesHeader[] = $role->name;

            $roleData[] = [
                'role' => $role->id,
                'permissions' => $routes
            ];
        }

        foreach ($routeCollection as $route) {
            $availableRoutes[] = $route;
        }

        $categorizedRoutes = [];
        foreach($availableRoutes as $route) {
            $routeElements = explode('.', $route);
            $categorizedRoutes[$routeElements[0].'.'.$routeElements[1]][] = end($routeElements);
        }



        // $used_category_routes = [];
        // foreach($categorizedRoutes as $category_route_key => $category_route) {
        //     foreach($category_route as $route_key => $route) {
        //         if(!in_array( $route, $used_category_routes )) {
        //             $used_category_routes[] = $route;
        //         } else {
        //             unset($categorizedRoutes[$category_route_key][$route_key]);
        //         }
        //     }
        // }

        // dd($this->helper->sortArray($categorizedRoutes));

        return view('settings.permissions.index', [
            'roleData' => $roleData,
            'rolesHeader' => $rolesHeader,
            'categorizedRoutes' => $this->helper->sortArray($categorizedRoutes)
        ]);
    }
}
