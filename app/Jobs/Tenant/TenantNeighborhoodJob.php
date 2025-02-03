<?php

namespace App\Jobs\Tenant;

use App\Models\Neighborhood;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Jobs\TenantAware;

class TenantNeighborhoodJob implements ShouldQueue, TenantAware
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $tenantId,
        public Collection|Neighborhood $neighborhood
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $id = ['id' => $this->neighborhood->id];
            $neighborhood = collect($this->neighborhood->toArray())->except('id')->toArray();

            DB::connection('tenant')->table('neighborhoods')->updateOrInsert(
                $id,
                $neighborhood
            );
        }
        catch (\Exception $e) {
            Log::error("Şehir eklenirken hata oluştu: {$e->getMessage()}");
        }
    }
}
