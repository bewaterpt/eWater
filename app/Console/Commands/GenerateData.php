<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Hash;
use Route;
use Validator;
use Illuminate\Support\Str;
use App\Models\Role;
use App\User;

class GenerateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dataseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates necessary data in each table in the database';

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
        // Connect to the database
        $db = DB::connection('mysql');
        // Get connection PDO
        $pdo = $db->getPdo();
        // Init array that will contain all permission ids
        $permission_ids = [];
        $default_admin_user_name = 'Local Administrator';
        $default_admin_user_mail = 'administrator@bewater.com.pt';
        $default_admin_user_username = 'admin';
        $default_admin_user_pass = Str::random(16);
        $admin_user_name = $this->ask("Enter the new administrator\'s name", $default_admin_user_name);
        $admin_user_mail = $this->ask("Enter the new administrator\'s email", $default_admin_user_mail);
        $admin_user_username = $this->ask("Enter the new administrator\'s username", $default_admin_user_username);
        $admin_user_pass = $this->ask("Enter the new administrator\'s password", $default_admin_user_pass);

        $validator = Validator::make([
            'admin_user_name' => $admin_user_name,
            'admin_user_mail' => $admin_user_mail,
            'admin_user_username' => $admin_user_username,
            'admin_user_pass' => $admin_user_pass,
        ], [
            'admin_user_name' => ['required'],
            'admin_user_mail' => ['email'],
            'admin_user_username' => ['required', 'unique:users,username'],
            'admin_user_pass' => ['required', 'min:10'],
        ]);

        if ($validator->fails()) {
            $this->info('User wasn\'t created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        DB::beginTransaction();
        try {
            $this->comment('Creating local admin user');

            $db->insert('insert into users (name, email, username, password) values(?, ?, ?, ?)', [$admin_user_name, $admin_user_mail, $admin_user_username, Hash::make($admin_user_pass)]);
            $admin_user_id = $pdo->lastInsertId();
            $admin_user = User::find($admin_user_id);

            if (!$admin_user) {
                throw new Exception('Failed to create admin user');
            }

            $this->info("admin user created\n");
            $this->info("the password is: ");
            $this->comment($admin_user_pass);

            // Insert all Roles
            $this->comment('Insert default roles');
            $db->insert('insert into roles (name, slug) values(?, ?)', ['Administrador', 'admin']);
            $admin_role_id = $pdo->lastInsertId();
            $db->insert('insert into roles (name, slug) values(?, ?)', ['Operador', 'operator']);
            $db->insert('insert into roles (name, slug) values(?, ?)', ['Utilizador', 'user']);
            $this->info('Done');

            $this->comment('Assign admin role to admin user');
            $admin_user->roles()->attach($admin_role_id);
            $this->info('Done');

            // Insert all Permissions
            $this->comment('Create all the permissions');
            $routes = collect(Route::getRoutes())->map(function ($route) {
                return in_array('allowed', $route->gatherMiddleware()) ? $route->getName() : null;
            })->filter(function ($value) {
                return !is_null($value);
            });

            // print_r($routes);
            // die;

            $this->output->progressStart($routes->count());
            foreach ($routes as $route) {
                // $this->comment('Inserting '. $route);
                $db->insert('insert into permissions (route) values(?)', [$route]);
                array_push($permission_ids, $pdo->lastInsertId());
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
            $this->info('Done');
            // $db->insert('insert into permissions (route) values(?)', ['settings.users.view']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.users.edit']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.users.toggle_state']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.users.delete']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.users.deleted']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.work_types.list']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.work_types.view']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.work_types.edit']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.work_types.toggle_state']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.work_types.delete']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.work_types.deleted']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.delegations.list']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.delegations.view']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.delegations.edit']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.delegations.toggle_state']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.delegations.delete']);
            // array_push($permission_ids, $pdo->lastInsertId());
            // $db->insert('insert into permissions (route) values(?)', ['settings.delegations.deleted']);
            // array_push($permission_ids, $pdo->lastInsertId());

            $this->info('Attach all permissions to admin role');
            Role::find($admin_role_id)->permissions()->attach($permission_ids);
            $this->comment('Done');
            DB::commit();
            $this->info('Finished seeding database');
        } catch(\Exception $e) {
            DB::rollback();
            echo $e->getMessage();
        }
    }
}
