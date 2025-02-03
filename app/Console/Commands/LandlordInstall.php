<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant;

class LandlordInstall extends Command
{
    protected Model $admin;

    protected AdminService $adminService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'landlord:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'First Roles And Permissions';

    public function __construct(AdminService $adminService)
    {
        parent::__construct();
        $this->adminService = $adminService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin_data = [
            'name' => 'Mustafa KARAGÜLLE',
            'email' => 'mustafacelalettinkaragulle@gmail.com',
        ];

        $admin = Admin::query()->where($admin_data);
        if(!$admin->exists()){
            $admin_data['password'] = bcrypt(123123);
            $this->admin = $this->adminService->create($admin_data);

        }

        $this->admin = $admin->first();

        $this->info($this->admin);

        $guards = [
            'admin' => [
                'create admins', 'read admins', 'update admins', 'delete admins',
                'create roles', 'read roles', 'update roles', 'delete roles',
                'create permissions', 'read permissions', 'update permissions', 'delete permissions',

                'create cities', 'read cities', 'update cities', 'delete cities',
                'create districts', 'read districts', 'update districts', 'delete districts',
                'create neighborhoods', 'read neighborhoods', 'update neighborhoods', 'delete neighborhoods',
                'create localities', 'read localities', 'update localities', 'delete localities',

                'create dealer_type_categories', 'read dealer_type_categories', 'update dealer_type_categories', 'delete dealer_type_categories',
                'create dealer_types', 'read dealer_types', 'update dealer_types', 'delete dealer_types',

                'create dealers', 'read dealers', 'update dealers', 'delete dealers',
                'create dealer_addresses', 'read dealer_addresses', 'update dealer_addresses', 'delete dealer_addresses',
                'create dealer_banks', 'read dealer_banks', 'update dealer_banks', 'delete dealer_banks',
                'create dealer_officers', 'read dealer_officers', 'update dealer_officers', 'delete dealer_officers',
                'create dealer_files', 'read dealer_files', 'update dealer_files', 'delete dealer_files',
                'create dealer_logos', 'read dealer_logos', 'update dealer_logos', 'delete dealer_logos',
                'create dealer_groups', 'read dealer_groups', 'update dealer_groups', 'delete dealer_groups',
                'create dealer_sectors', 'read dealer_sectors', 'update dealer_sectors', 'delete dealer_sectors',

                'create users', 'read users', 'update users', 'delete users',
                'create customers', 'read customers', 'update customers', 'delete customers',

                'create years', 'read years', 'update years', 'delete years',

                'create vehicle_brands', 'read vehicle_brands', 'update vehicle_brands', 'delete vehicle_brands',
                'create vehicle_tickets', 'read vehicle_tickets', 'update vehicle_tickets', 'delete vehicle_tickets',
                'create vehicle_models', 'read vehicle_models', 'update vehicle_models', 'delete vehicle_models',

                'create vehicle_property_categories', 'read vehicle_property_categories', 'update vehicle_property_categories', 'delete vehicle_property_categories',
                'create vehicle_properties', 'read vehicle_properties', 'update vehicle_properties', 'delete vehicle_properties',
                'create vehicles', 'read vehicles', 'update vehicles', 'delete vehicles',

                'create banks', 'read banks', 'update banks', 'delete banks',
                'create groups', 'read groups', 'update groups', 'delete groups',
                'create sectors', 'read sectors', 'update groups', 'delete groups',

                'create account_type_categories', 'read account_type_categories', 'update account_type_categories', 'delete account_type_categories',
                'create account_types', 'read account_types', 'update account_types', 'delete account_types',

                'create accounts', 'read accounts', 'update accounts', 'delete accounts',
                'create account_addresses', 'read account_addresses', 'update account_addresses', 'delete account_addresses',
                'create account_banks', 'read account_banks', 'update account_banks', 'delete account_banks',
                'create account_officers', 'read account_officers', 'update account_officers', 'delete account_officers',
                'create account_files', 'read account_files', 'update account_files', 'delete account_files',
                'create account_groups', 'read account_groups', 'update account_groups', 'delete account_groups',
                'create account_sectors', 'read account_sectors', 'update account_sectors', 'delete account_sectors',


                'create hgs_type_categories', 'read hgs_type_categories', 'update hgs_type_categories', 'delete hgs_type_categories',
                'create hgs_types', 'read hgs_types', 'update hgs_types', 'delete hgs_types',


                'create licence_type_categories', 'read licence_type_categories', 'update licence_type_categories', 'delete licence_type_categories',
                'create licence_types', 'read licence_types', 'update licence_types', 'delete licence_types',


                'create staff_type_categories', 'read staff_type_categories', 'update staff_type_categories', 'delete staff_type_categories',
                'create staff_types', 'read staff_types', 'update staff_types', 'delete staff_types',

            ],
        ];

        foreach ($guards as $guard_name => $permissions) {
            $role_data = ['name' => $guard_name, 'guard_name' => $guard_name];

            $role = new Role();
            $role->setConnection('landlord');

            $r = $role->where($role_data)->exists() ? $role->where($role_data)->first() : $role->create($role_data);
            $this->info($r);

            foreach ($permissions as $permission) {
                $permission_data = ['name' => $permission, 'guard_name' => $guard_name];

                $p = new Permission();
                $p->setConnection('landlord');

                $per = $p->where($permission_data)->exists() ? $p->where($permission_data)->first() : $p->create($permission_data);

                $this->info($per);
                $r->givePermissionTo($per->name);
            }
        }
        $this->admin->assignRole('admin');

        DB::table('tenants')->updateOrInsert(['name' => 'Aztekin'], ['domain' => 'aztekin.test', 'database' => 'aztekin']);
        DB::table('tenants')->updateOrInsert(['name' => 'Atlas'], ['domain' => 'atlas.test', 'database' => 'atlas']);
    }
}
