<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Role;
use LdapRecord\Connection;
use LdapRecord\Models\ActiveDirectory\Group;


class UpdateADGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Periodically updates roles based on Active directory groups';

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
        $groups = Group::in("ou=" . config('ldap.ou_filter') . ",".config('ldap.connections.default.base_dn'))->get();
        $rolesToRemove = Role::whereNotIn('id', [1, 2, 3])->pluck('slug');

        DB::beginTransaction();

        try {
            $this->comment('Update/Insert Domain roles');
            foreach($groups as $group) {
                $role = Role::withTrashed()->where('slug', mb_strtolower($group->cn[0]))->first();
                if ($role) {

                    if ($role->trashed()) {
                        $this->info('Restoring deleted group ' . $group['samaccountname'][0]);
                        $role->restore();
                    }

                    if ($role->name !== $group->samaccountname[0]) {
                        $this->info('Updating group name from ' . $role->name . ' to ' . $group->samaccountname[0]);
                        $role->name = $group->samaccountname[0];
                        $role->save();

                    }

                } else {
                    $db->insert('insert into roles (name, slug) values(?, LOWER(?))', [$group['samaccountname'][0], $group['cn'][0]]);
                    $this->info('Inserted new group ' . $group['samaccountname'][0]);
                }

                $rolesToRemove = $rolesToRemove->reject(function ($value) use($group) {
                    return $value === mb_strtolower($group->cn[0]);
                });
            }

            foreach($rolesToRemove->values() as $roleToRemove) {
                $roleToRemove = Role::where('slug', $roleToRemove)->first();
                $this->info('Deleting Application Role '.$roleToRemove->name);
                $roleToRemove->permissions()->detach();
                $roleToRemove->users()->detach();
                $roleToRemove->delete();
            }

            $this->info('Done');
            $this->info('');

            DB::commit();
        } catch(\Exception $e) {
            // On error rollback changes
            DB::rollback();
            $this->error($e->getMessage());
        }

        $ldap->disconnect();
    }
}
