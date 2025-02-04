<?php

namespace App\Jobs\Tenant;

use App\Models\City;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Jobs\TenantAware;

class TenantSyncDataJob implements ShouldQueue, TenantAware
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $tenantId,
        public array $dataId,
        public array $data,
        public string $tableName,
        public string $errorMessage
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::connection('tenant')->table($this->tableName)->updateOrInsert(
                $this->dataId,
                $this->data
            );
        }
        catch (\Exception $e) {
            Log::error("{$this->errorMessage} {$e->getMessage()}");
        }
    }
}
