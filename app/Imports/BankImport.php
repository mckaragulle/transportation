<?php

namespace App\Imports;

use App\Models\Bank;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Jobs\NotTenantAware;

class BankImport implements ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, NotTenantAware
{   
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $row = array_values($row);
        try {
            // Log::info($row[0]);
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
        } catch (\Exception $e) {
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
