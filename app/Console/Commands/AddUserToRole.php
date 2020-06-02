<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\User;

class AddUserToRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:adduser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Associate a certain user with a role';

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
        $headers = ['Role ID', 'Role Name', 'User ID', 'User Name'];

        $data = [];

        $roles = Role::all();
        $users = User::all();
        $i = 0;

        foreach($roles as $role) {
            if (isset($users[$i])) {
                array_push($data, ['role_id' => $role->id, 'role_name' => $role->name, 'user_id' => $users[$i]->id, 'user_name' => $users[$i]->name]);
            } else {
                array_push($data, ['role_id' => $role->id, 'role_name' => $role->name, 'user_id' => '', 'user_name' => '']);
            }
            $i++;
        }

        $this->table($headers, $data);
        $this->info('');
        $roleId = $this->ask('Please enter the role id to which you wish to add the user');
        $userIds = explode(',', $this->ask('Please insert the user id of the user you with to associate to the role, can be a csl with no spaces.'));
        $role = Role::find($roleId);
        $role->users()->attach($userIds);
    }
}
