<?php

namespace App\Console\Commands;

use App\Services\AdminService;
use App\Services\DealerService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppInstall extends Command
{
    protected Model $user;
    protected Model $dealer;

    protected AdminService $adminService;
    protected DealerService $dealerService;

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

    public function __construct(AdminService $adminService, DealerService $dealerService)
    {
        parent::__construct();
        $this->adminService = $adminService;
        $this->dealerService = $dealerService;
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
        $this->user = $this->adminService->create($user);

        $dealer = [
            "name" => "Gaziantep MERKEZ",
            "email" => "gaziantep@app.com",
            "password" => bcrypt(123123),
            "phone" => "05545559411",
        ];
        $this->dealer = $this->dealerService->create($dealer);

        $guards = [
            'admin' => [
                'create admins', 'read admins', 'update admins', 'delete admins',
                'create roles', 'read roles', 'update roles', 'delete roles',
                'create permissions', 'read permissions', 'update permissions', 'delete permissions',

                'create dealers', 'read dealers', 'update dealers', 'delete dealers',
                'create users', 'read users', 'update users', 'delete users',
                'create customers', 'read customers', 'update customers', 'delete customers',

                'create years', 'read years', 'update years', 'delete years',

                'create vehicle_brands', 'read vehicle_brands', 'update vehicle_brands', 'delete vehicle_brands',
                'create vehicle_tickets', 'read vehicle_tickets', 'update vehicle_tickets', 'delete vehicle_tickets',
                'create vehicle_models', 'read vehicle_models', 'update vehicle_models', 'delete vehicle_models',
                'create vehicle_property_categories', 'read vehicle_property_categories', 'update vehicle_property_categories', 'delete vehicle_property_categories',
                'create vehicle_properties', 'read vehicle_properties', 'update vehicle_properties', 'delete vehicle_properties',
                
                'create account_type_categories', 'read account_type_categories', 'update account_type_categories', 'delete account_type_categories',
                'create account_types', 'read account_types', 'update account_types', 'delete account_types',
                
                'create hgs_type_categories', 'read hgs_type_categories', 'update hgs_type_categories', 'delete hgs_type_categories',
                'create hgs_types', 'read hgs_types', 'update hgs_types', 'delete hgs_types',
                'create hgses', 'read hgses', 'update hgses', 'delete hgses',



            ],

            'bayi' => [
                'create users', 'read users', 'update users', 'delete users',
                'create customers', 'read customers', 'update customers', 'delete customers',
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
            $role_data = ['name' => $guard_name, 'guard_name' => 'admin'];

            $r = Role::where($role_data);
            if (!$r->exists()) {
                $role = Role::create($role_data);
            } else {
                $role = $r->first();
            }
            $this->info($role);

            foreach ($permissions as $permission) {
                $permission_data = ['name' => $permission, 'guard_name' => 'admin'];
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
        $this->user->assignRole('admin');
        $this->dealer->assignRole('bayi');
    }
}
