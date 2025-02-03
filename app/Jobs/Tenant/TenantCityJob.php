<?php

namespace App\Jobs\Tenant;

use App\Models\City;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Jobs\TenantAware;

class TenantCityJob implements ShouldQueue, TenantAware
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $tenantId,
        public Collection|City $city
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $id = ['id' => $this->city->id];
            $city = collect($this->city->toArray())->except('id')->toArray();

            DB::connection('tenant')->table('cities')->updateOrInsert(
                $id,
                $city
            );
        }
        catch (\Exception $e) {
            Log::error("Şehir eklenirken hata oluştu: {$e->getMessage()}");
        }
    }
}
