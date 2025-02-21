<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant\Dealer;
use App\Services\Tenant\DealerService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Multitenancy\Models\Tenant;

class TenantInstall extends Command
{
    use TenantAware;
    protected Model $dealer;

    protected DealerService $dealerService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:install {--tenant=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'First Roles And Permissions';

    public function __construct(DealerService $dealerService)
    {
        parent::__construct();
        $this->dealerService = $dealerService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenant = Tenant::current();
        $this->line("The tenant is: {$tenant->name}");

        $name = Str::upper(app('currentTenant')->name);
        $email = Str::lower(app('currentTenant')->name);
        $dealer_data = [
            "name" => $name,
            "email" => "{$email}@app.com",
            "phone" => "05545559411",
            "shortname" => $name,
            "taxoffice" => "Gazikent",
            "detail" => 'Açıklama',
        ];

        $dealer = Dealer::query()->where($dealer_data);
        if(!$dealer->exists()){
            $dealer_data['password'] = bcrypt(123123);
            $dealer_data['number'] = random_int(000000000000, 999999999999);
            $dealer_data['tax'] = random_int(00000000000, 99999999999);
            $this->dealer = $this->dealerService->create($dealer_data);
        }

        $this->dealer = $dealer->first();

        $guards = [
            'dealer' => [
                'create users', 'read users', 'update users', 'delete users',
                'create customers', 'read customers', 'update customers', 'delete customers',

                'create dealer_addresses', 'read dealer_addresses', 'update dealer_addresses', 'delete dealer_addresses',
                'create dealer_banks', 'read dealer_banks', 'update dealer_banks', 'delete dealer_banks',
                'create dealer_officers', 'read dealer_officers', 'update dealer_officers', 'delete dealer_officers',
                'create dealer_files', 'read dealer_files', 'update dealer_files', 'delete dealer_files',
                'create dealer_logos', 'read dealer_logos', 'update dealer_logos', 'delete dealer_logos',
                'create dealer_groups', 'read dealer_groups', 'update dealer_groups', 'delete dealer_groups',
                'create dealer_sectors', 'read dealer_sectors', 'update dealer_sectors', 'delete dealer_sectors',
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

                'create licence_type_categories', 'read licence_type_categories', 'update licence_type_categories', 'delete licence_type_categories',
                'create licence_types', 'read licence_types', 'update licence_types', 'delete licence_types',
                'create licences', 'read licences', 'update licences', 'delete licences',

                'read staff_type_categories',
                'read staff_types',
                'create staffs', 'read staffs', 'update staffs', 'delete staffs',
                'create staff_competences', 'read staff_competences', 'update staff_competences', 'delete staff_competences',
                'create staff_addresses', 'read staff_addresses', 'update staff_addresses', 'delete staff_addresses',
                'create staff_banks', 'read staff_banks', 'update staff_banks', 'delete staff_banks',
                'create staff_files', 'read staff_files', 'update staff_files', 'delete staff_files',

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
            $role_data = ['name' => $guard_name, 'guard_name' => $guard_name];

            $role = new Role();
            $role->setConnection('tenant');

            if(!$role->where($role_data)->exists()){
                $role = new Role();
                $role->setConnection('tenant');
                $role->name = $guard_name;
                $role->guard_name = $guard_name;
                $role->save();
            }
            $r = $role->where($role_data)->first();
            $this->info($r);

            foreach ($permissions as $permission) {
                $permission_data = ['name' => $permission, 'guard_name' => $guard_name];
                $p = new Permission();
                $p->setConnection('tenant');

                if(!$p->where($permission_data)->exists()){
                    $p = new Permission();
                    $p->setConnection('tenant');
                    $p->name = $permission;
                    $p->guard_name = $guard_name;
                    $p->save();

                    $per = $p->where($permission_data)->first();
                    $r->givePermissionTo($per);
                }
            }
        }

        $this->line($this->dealer->name);
        $role = new Role();
        $role->setConnection('tenant');
        $r = $role->where('name', 'dealer')->first();
        $this->dealer->assignRole($r);
    }
}
