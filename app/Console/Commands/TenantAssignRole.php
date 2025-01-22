<?php

namespace App\Console\Commands;

use App\Models\Dealer;
use Illuminate\Console\Command;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Str;

class TenantAssignRole extends Command
{
    use TenantAware;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:assignrole {--tenant=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenant = Tenant::current();
        $this->line("The tenant is: {$tenant->name}");
        $dealer = new Dealer();
        $dealer->setConnection('tenant');
        $dealer = $dealer->where('slug', Str::lower($tenant->name))->first();
        // $dealer = Dealer::query()->where('name', Str::lower($tenant->name))->first();
        $this->line($dealer);
        $dealer->setConnection('tenant');
    }
}
