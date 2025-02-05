<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\Bank;
use Spatie\Multitenancy\Models\Tenant;

class BankObserver
{
    /**
     * Handle the Bank "created" event.
     */
    public function created(Bank $bank): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($bank) {
            $data = getTenantSyncDataJob($bank);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'banks', 'Banka Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the Bank "updated" event.
     */
    public function updated(Bank $bank): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($bank) {
            $data = getTenantSyncDataJob($bank);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'banks', 'Banka Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the Bank "deleted" event.
     */
    public function deleted(Bank $bank): void
    {
        //
    }

    /**
     * Handle the Bank "restored" event.
     */
    public function restored(Bank $bank): void
    {
        //
    }

    /**
     * Handle the Bank "force deleted" event.
     */
    public function forceDeleted(Bank $bank): void
    {
        //
    }
}
