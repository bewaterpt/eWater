<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Hash;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\User;

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

        $admin_pass = Str::random(16);

        echo "admin user created\n";
        echo "password is: ".$admin_pass;

        $db->insert('insert into users (name, email, username, password) values(?, ?, ?, ?)', ['Administrador', 'administrator@bewater.com.pt', 'admin', Hash::make($admin_pass)]);
        $admin_user_id = $pdo->lastInsertId();
        // Insert all Roles
        $db->insert('insert into roles (name, slug) values(?, ?)', ['Administrador', 'admin']);
        $admin_role_id = $pdo->lastInsertId();
        $db->insert('insert into roles (name, slug) values(?, ?)', ['Operador', 'operator']);
        $db->insert('insert into roles (name, slug) values(?, ?)', ['Utilizador', 'user']);

        $db->insert('insert into role_user (role_id, user_id) values(?, ?)', [$admin_role_id, $admin_user_id]);

        // Insert all Permissions
        $db->insert('insert into permissions (route) values(?)', ['settings.users.list']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.users.view']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.users.edit']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.users.toggle_state']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.users.delete']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.users.deleted']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.work_types.list']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.work_types.view']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.work_types.edit']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.work_types.toggle_state']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.work_types.delete']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.work_types.deleted']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.delegations.list']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.delegations.view']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.delegations.edit']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.delegations.toggle_state']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.delegations.delete']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings.delegations.deleted']);
        array_push($permission_ids, $pdo->lastInsertId());

        Role::find($admin_role_id)->permissions()->attach($permission_ids);
    }
}
