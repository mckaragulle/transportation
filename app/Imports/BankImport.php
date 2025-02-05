<?php

namespace App\Imports;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\Bank;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Spatie\Multitenancy\Jobs\NotTenantAware;
use Spatie\Multitenancy\Models\Tenant;

class BankImport implements ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, NotTenantAware
{
    public ?Bank $bank = null;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        DB::beginTransaction();
        $row = array_values($row);
        try {
            $whereData = [
                'name' => Str::trim($row[0] ?? null),
                'phone' => Str::trim($row[1] ?? null),
                'fax' => Str::trim($row[2] ?? null),
                'website' => Str::trim($row[3] ?? null),
                'email' => Str::trim($row[4] ?? null),
                'eft' => Str::trim($row[5] ?? null),
                'swift' => Str::trim($row[6] ?? null),
                'status' => true,
            ];
            $bank = Bank::query();
            if(!$bank->where($whereData)->exists()){
                $this->bank = $bank->create($whereData);
                Tenant::all()->eachCurrent(function(Tenant $tenant) {
                    $data = getTenantSyncDataJob($this->bank);
                    TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'banks', 'Bankalar Eklenirken Hata Oluştu.')->delay(now()->addMinute());
                });
            }
            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
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
