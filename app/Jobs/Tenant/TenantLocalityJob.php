<?php

namespace App\Jobs\Tenant;

use App\Models\Locality;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Jobs\TenantAware;

class TenantLocalityJob implements ShouldQueue, TenantAware
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $tenantId,
        public Collection|Locality $locality
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $id = ['id' => $this->locality->id];
            $locality = collect($this->locality->toArray())->except('id')->toArray();

            DB::connection('tenant')->table('localities')->updateOrInsert(
                $id,
                $locality
            );
        }
        catch (\Exception $e) {
            Log::error("Mahalle eklenirken hata oluştu: {$e->getMessage()}");
        }
    }
}
