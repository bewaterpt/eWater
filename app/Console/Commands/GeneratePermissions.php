<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class GeneratePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates the permissions table in the database';

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

        // Insert all Roles
        $db->insert('insert into roles (name, slug) values(?, ?)', ['Administrador', 'admin']);
        $admin_role_id = $pdo->lastInsertId();
        $db->insert('insert into roles (name, slug) values(?, ?)', ['Utilizador', 'user']);

        // Insert all Permissions
        $db->insert('insert into permissions (route) values(?)', ['home']);
        array_push($permission_ids, $pdo->lastInsertId());
        $db->insert('insert into permissions (route) values(?)', ['settings/users']);
        array_push($permission_ids, $pdo->lastInsertId());

        // Build permission_role relation to admin role
        foreach($permission_ids as $permission_id) {
            $db->insert('insert into permission_role (permission_id, role_id) values(?, ?)', [$permission_id, $admin_role_id]);
        }
    }
}
