<?php

namespace App\Jobs\Tenant;

use App\Models\District;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Jobs\TenantAware;

class TenantDistrictJob implements ShouldQueue, TenantAware
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $tenantId,
        public Collection|District $district
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $id = ['id' => $this->district->id];
            $district = collect($this->district->toArray())->except('id')->toArray();

            DB::connection('tenant')->table('districts')->updateOrInsert(
                $id,
                $district
            );
        }
        catch (\Exception $e) {
            Log::error("İlçe eklenirken hata oluştu: {$e->getMessage()}");
        }
    }
}
