<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;
use App\Helpers\Helper;


class CreateNewRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a role via command line';

    protected $db;

    protected $pdo;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->helper = new Helper();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $roleName = $this->ask("Enter the new role\'s name");
        $defaultRoleSlug = $this->helper->transliterate($roleName, 1);
        $roleSlug = $this->ask("Enter the new role\'s slug", $defaultRoleSlug);

        $role = new Role();
        $role->name = $roleName;
        $role->slug = $roleSlug;
        $role->save();
    }
}
