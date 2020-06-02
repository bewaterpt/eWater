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

        $admin_role = Role::where('slug', 'admin')->first();
        $permission_model = new Permission();

        $this->comment('Create added permissions');

        $permission_ids = [];
        $routes = collect(Route::getRoutes())->map(function ($route) {
            return in_array('allowed', $route->gatherMiddleware()) ? $route->getName() : null;
        })->filter(function ($value) {
            return !is_null($value);
        });

        $routesToUpdate = [];

        foreach ($routes as $route) {
            if (!$permission_model->existsByRoute($route)) {
                $routesToUpdate[] = $route;
            }
        }

        $this->output->progressStart(sizeof($routesToUpdate));
        foreach ($routesToUpdate as $route) {
            $db->insert('insert into permissions (route) values(?)', [$route]);
            array_push($permission_ids, $pdo->lastInsertId());
            $this->output->progressAdvance();
        }


        $admin_role->permissions()->attach($permission_ids);
        $this->output->progressFinish();
        $this->info('Done');
        $this->info('');
    }
}
