<?php

namespace App\Jobs;

use App\Models\Bank;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Illuminate\Support\Str;

class TenantImportBankJob implements ShouldQueue
{
    use Queueable;
    use TenantAware;

    public null|string|int $tenantId;

    /**
     * Create a new job instance.
     */
    public function __construct(null|string|int $tenantId, array $row)
    {
        $this->tenantId = $tenantId;

        Bank::firstOrCreate([
            'name' => Str::trim($row[0] ?? null),
            'phone' => Str::trim($row[1] ?? null),
            'fax' => Str::trim($row[2] ?? null),
            'website' => Str::trim($row[3] ?? null),
            'email' => Str::trim($row[4] ?? null),
            'eft' => Str::trim($row[5] ?? null),
            'swift' => Str::trim($row[6] ?? null),
            'status' => true,
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
