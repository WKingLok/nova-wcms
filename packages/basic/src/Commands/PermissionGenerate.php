<?php

namespace Packages\Basic\Commands;

use Illuminate\Console\Command;
use Packages\Basic\Models\Permission;
use Packages\Basic\Models\Role;

class PermissionGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Permissions, Roles';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $permissionConfig = config('wcms.permission');
        $roleConfig = config('wcms.role');

        //generage permissions
        foreach ($permissionConfig as $guard => $permissions) {
            foreach ($permissions as $permission) {
                try {
                    if (!Permission::where('name', $permission)->where('guard_name', $guard)->first()) {
                        Permission::create([
                            'name' =>  $permission['key'],
                            'guard_name' => $guard
                        ]);
                        $this->info("Permission {$permission['label']} --guard $guard created.");
                    }
                } catch (\Throwable $th) {
                    Command::FAILURE;
                }
            }
        }

        //generage roles
        foreach ($roleConfig as $guard => $roles) {
            foreach ($roles as $role) {
                try {
                    if (!Role::where('name', $role)->where('guard_name', $guard)->first()) {
                        Role::create([
                            'name' =>  $role,
                            'guard_name' => $guard
                        ]);
                        $this->info("Role $role --guard $guard created.");
                    }
                } catch (\Throwable $th) {
                    Command::FAILURE;
                }
            }
        }

        Command::SUCCESS;
    }
}
