<?php

namespace App\Imports;

use App\Jobs\TenantImportBankJob;
use App\Models\Bank;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Str;

class BankImport implements ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    use TenantAware;

    public null|int|string $tenantId;

    public function __construct(null|int|string $tenantId) {
        $this->tenantId = $tenantId;
    }
    
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $row = array_values($row);
        try {
            
            $tenant = Tenant::find($this->tenantId);

            if ($tenant) {
                $tenant->makeCurrent();
            }
            
                // TenantImportBankJob::dispatch($tenant->id, $row)->now();
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
                $tenant->forgetCurrent();

        } catch (\Exception $e) {
            Log::error($row);
            Log::error($e->getMessage());
        }
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
