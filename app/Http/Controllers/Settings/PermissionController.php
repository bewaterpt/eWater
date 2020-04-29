<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use Route;

class PermissionController extends Controller
{
    public function index(Request $request) {
        // Get all the available roles
        $roles = Role::all();

        $role_data = [];
        $roles_header = [ __('global.permission') ];
        $available_routes = [];

        $routeCollection = Route::getRoutes();

        foreach( $roles as $role ){
            $routes = [];
            foreach ($routeCollection as $value) {
                if ($value->getName() && in_array('allowed', $value->middleware())) {
                    $route_elements = explode('/', $value->getName());

                    $routes[$route_elements[0]][] = [
                        'checked' => $this->permission_model->can($value->getName()),
                        'route' => $value->getName()
                    ];

                }
            }
            $roles_header[] = $role->name;

            $role_data[] = [
                'role' => $role->id,
                'permissions' => $routes
            ];
        }
        foreach ($routeCollection as $value) {
            if ($value->getName() && in_array('allowed', $value->middleware())) {
                $available_routes[] = $value->getName();
            }
        }
        $categorized_routes = [];
        foreach( $available_routes as $route ){
            $route_elements = explode('.', $route);
            $categorized_routes[$route_elements[0]][] = end( $route_elements );
        }


        $used_category_routes = [];
        foreach( $categorized_routes as $category_route_key => $category_route ){
            foreach( $category_route as $route_key => $route ){
                if( ! in_array( $route, $used_category_routes ) ){
                    $used_category_routes[] = $route;
                }else{
                    unset( $categorized_routes[$category_route_key][$route_key] );
                }
            }
        }

        return view('settings.permissions.index', [
            'role_data' => $role_data,
            'roles_header' => $roles_header,
            'categorized_routes' => $categorized_routes
        ]);
    }
}
