<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Hash;
use Route;
use Validator;
use App\Models\Role;
use Illuminate\Support\Str;
use App\User;
use LdapRecord\Connection;

class GenerateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:initialize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates necessary data in each table in the database';

    protected $db;

    protected $pdo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->db = DB::connection('mysql');
        $this->pdo = $this->db->getPdo();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // Connect to ldap to populate allowed groups table
        $ldap = new Connection(config('ldap.connections.'.config('ldap.default')));

        try {
            $ldap->connect();

            $this->info("LDAP Connection successful.");
        } catch (\LdapRecord\Auth\BindException $e) {
            $error = $e->getDetailedError();

            $this->info('An error occured while connecting to the ldap server, see details bellow:');
            $this->error($error->getErrorCode() . ' ' . $error->getErrorMessage() . ' ' . $error->getDiagnosticMessage());
            return;
        }

        // Get AD Groups to populate ad_groups table, which is merely a helper table
        $query = $ldap->query();
        $query->select(['samaccountname', 'cn']);
        $groups = $query->where('cn', 'starts_with', config('ldap.starts_with_filter'))->get();

        // Init array that will contain all permission ids
        $permission_ids = [];

        // Set admin user defaults
        $default_admin_user_name = 'Local Administrator';
        $default_admin_user_mail = 'administrator@bewater.com.pt';
        $default_admin_user_username = 'admin';
        $default_admin_user_pass = Str::random(16);

        // Ask user for admin data
        $admin_user_name = $this->ask("Enter the new administrator\'s name", $default_admin_user_name);
        $admin_user_mail = $this->ask("Enter the new administrator\'s email", $default_admin_user_mail);
        $admin_user_username = $this->ask("Enter the new administrator\'s username", $default_admin_user_username);
        $admin_user_pass = $this->ask("Enter the new administrator\'s password", $default_admin_user_pass);

        // Validate admin data
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

        // Error on validation failed
        if ($validator->fails()) {
            $this->info('User wasn\'t created. See error messages below:');

            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        // Begin SQL transaction
        DB::beginTransaction();

        try {
            $this->comment('Creating local admin user');

            $this->db->insert('insert into users (name, email, username, password) values(?, ?, ?, ?)', [$admin_user_name, $admin_user_mail, $admin_user_username, Hash::make($admin_user_pass)]);
            $admin_user_id = $this->pdo->lastInsertId();
            $admin_user = User::find($admin_user_id);

            if (!$admin_user) {
                throw new Exception('Failed to create admin user');
            }

            $this->info("admin user created\n");
            $this->info("the password is: ");
            $this->comment($admin_user_pass);
            $this->info('');
            // Insert all Roles

            $this->comment('Insert default roles');
            $this->db->insert('insert into roles (name, slug) values(?, ?)', ['Administrador', 'admin']);
            $admin_role_id = $this->pdo->lastInsertId();
            $this->db->insert('insert into roles (name, slug) values(?, ?)', ['Operador', 'operator']);
            $this->db->insert('insert into roles (name, slug) values(?, ?)', ['Utilizador', 'user']);
            $this->info('Done');
            $this->info('');

            $this->comment('Insert Domain roles');
            foreach($groups as $group) {
                $this->db->insert('insert into roles (name, slug) values(?, LOWER(?))', [$group['samaccountname'][0], $group['cn'][0]]);
            }
            $this->info('Done');
            $this->info('');

            $this->comment('Assign admin role to admin user');
            $admin_user->roles()->attach($admin_role_id);
            $this->info('Done');
            $this->info('');

            $this->comment('Create delegations');
            $this->db->insert('insert into delegations (designation) values(?)', ['Ourém']);
            $this->info('Done');
            $this->info('');

            // Insert all Permissions
            $this->comment('Create all the permissions');
            $routes = collect(Route::getRoutes())->map(function ($route) {
                return in_array('allowed', $route->gatherMiddleware()) ? $route->getName() : null;
            })->filter(function ($value) {
                return !is_null($value);
            });

            $this->output->progressStart($routes->count());
            foreach ($routes as $route) {
                // $this->comment('Inserting '. $route);
                $this->db->insert('insert into permissions (route) values(?)', [$route]);
                array_push($permission_ids, $this->pdo->lastInsertId());
                $this->output->progressAdvance();
            }
            $this->output->progressFinish();
            $this->info('Done');
            $this->info('');

            $this->comment('Attach all permissions to admin role');
            Role::find($admin_role_id)->permissions()->attach($permission_ids);
            $this->info('Done');
            $this->info('');

            $this->comment('Insert process statuses');
            $this->db->insert('insert into statuses (name, slug) values(?, ?)', ['Criado', 'created']);
            // $this->db->insert('insert into statuses (name, slug) values(?, ?)', ['Em Edição', 'editing']);
            $this->db->insert('insert into statuses (name, slug) values(?, ?)', ['Estado Extra', 'extra']);
            $this->db->insert('insert into statuses (name, slug) values(?, ?)', ['Validação', 'validation']);
            $this->db->insert('insert into statuses (name, slug) values(?, ?)', ['Aprovação', 'approval']);
            $this->db->insert('insert into statuses (name, slug) values(?, ?)', ['Sincronização BD', 'database_sync']);
            $this->db->insert('insert into statuses (name, slug) values(?, ?)', ['TERMINADO', 'finish']);
            $this->db->insert('insert into statuses (name, slug) values(?, ?)', ['CANCELADO', 'cancel']);
            $this->info('Done');
            $this->info('');

            // On completion commit transaction data
            DB::commit();

            config('app.initialized', true);
            $this->info('Finished seeding database');
        } catch(\Exception $e) {
            // On error rollback changes
            DB::rollback();
            $this->info('');
            $this->info('Unexpected error ocurred, see message bellow:');
            $this->error($e->getMessage());
        }

        $ldap->disconnect();
    }
}
