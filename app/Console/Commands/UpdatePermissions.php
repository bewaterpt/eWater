<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permission;
use App\Models\Role;
use Route;
use DB;

class UpdatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periodically updates permissions in the database to add new routes if they exist';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $db = DB::connection('mysql');
        $pdo = $db->getPdo();

        $adminRole = Role::where('slug', 'admin')->first();
        $permissionModel = new Permission();

        $this->comment('Adding created permissions');

        $permissionIds = [];
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return in_array('allowed', $route->gatherMiddleware()) ? $route->getName() : null;
        })->filter(function ($value) {
            return !is_null($value);
        });

        $routesToAdd = [];

        foreach ($routes as $route) {
            if (!$permissionModel->existsByRoute($route)) {
                $routesToAdd[] = $route;
            }
        }

        if (Sizeof($routesToAdd)) {
            $this->output->progressStart(sizeof($routesToAdd));
            foreach ($routesToAdd as $route) {
                $db->insert('insert into permissions (route) values(?)', [$route]);
                array_push($permissionIds, $pdo->lastInsertId());
                $this->output->progressAdvance();
            }

            $adminRole->permissions()->attach($permissionIds);
            $this->output->progressFinish();
        } else {
            $this->info('Nothing to add.');
            $this->info('');
        }

        $this->comment('Removing deleted permissions');
        $routesToRemove = [];
        $permissionIds = [];

        foreach (Permission::all()->pluck('route') as $routeName) {
            if (!$routes->filter(function($route) use ($routeName) { return $route === $routeName; })->first()) {
                $routesToRemove[] = $routeName;
                $permissionIds[] = Permission::where('route', $routeName)->first()->id;
            }
        }

        if(Sizeof($routesToRemove)) {
            $adminRole->permissions()->detach($permissionIds);
            $this->output->progressStart(sizeof($routesToRemove));
            foreach ($routesToRemove as $route) {
                $db->delete('delete from permissions where route = ?', [$route]);
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
        } else {
            $this->info('Nothing to remove.');
            $this->info('');
        }

        $this->info('Done');
        $this->info('');
    }
}
