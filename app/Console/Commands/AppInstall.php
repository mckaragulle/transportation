<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;

class AppInstall extends Command
{
    protected Model $user;

    protected UserService $userService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'First Roles And Permissions';

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService = $userService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = [
            'name' => 'Mustafa KARAGÜLLE',
            'email' => 'mustafacelalettinkaragulle@gmail.com',
            'password' => bcrypt(123123),
        ];
        $this->user = $this->userService->create($user);

        $guards = [
            'yonetici' => [
                'create users', 'read users', 'update users', 'delete users',
                'create customers', 'read customers', 'update customers', 'delete customers',

                'create accounts', 'read accounts', 'update accounts', 'delete accounts',
                'create account_addresses', 'read account_addresses', 'update account_addresses', 'delete account_addresses',
                'create account_banks', 'read account_banks', 'update account_banks', 'delete account_banks',
                'create account_officers', 'read account_officers', 'update account_officers', 'delete account_officers',
                'create account_files', 'read account_files', 'update account_files', 'delete account_files',
                'create account_groups', 'read account_groups', 'update account_groups', 'delete account_groups',
                'create account_sectors', 'read account_sectors', 'update account_sectors', 'delete account_sectors',
                
                'read cities',
                'read districts',
                'read neighborhoods',
                'read localities',
                'read banks',
                'read groups',
                'read sectors',

                'read hgs_type_categories',
                'read hgs_types',
                'create hgses', 'read hgses', 'update hgses', 'delete hgses',
                
                'read licence_type_categories',
                'read licence_types',
                'create licences', 'read licences', 'update licences', 'delete licences',
                
                'read staff_type_categories',
                'read staff_types',
                'create staffs', 'read staffs', 'update staffs', 'delete staffs',
                'create fineds', 'read fineds', 'update fineds', 'delete fineds',
            ],

            'satis' => [
                'create users', 'read users', 'update users', 'delete users',
            ],

            'muhasebe' => [
                'create users', 'read users', 'update users', 'delete users',
            ],

            'depo' => [
                'create users', 'read users', 'update users', 'delete users',
            ],
        ];

        foreach ($guards as $guard_name => $permissions) {
            // $g = in_array($guard_name, ['admin', 'dealer']) ? $guard_name : 'web';
            // $uuid = Str::uuid();
            $role_data = ['name' => $guard_name, 'guard_name' => $guard_name];

            $r = Role::where($role_data);
            if (!$r->exists()) {
                $role = Role::create($role_data);
            } else {
                $role = $r->first();
            }
            $this->info($role);

            foreach ($permissions as $permission) {
                $permission_data = ['name' => $permission, 'guard_name' => $guard_name];
                $p = Permission::where($permission_data);
                if (!$p->exists()) {
                    $p = Permission::create($permission_data);
                } else {
                    $p = $p->first();
                }
                $this->info($p);
                $role->givePermissionTo($p->name);
            }
        }
        $this->user->assignRole('yonetici');
    }
}