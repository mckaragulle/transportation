<?php

namespace App\Observers\Landlord;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\LandlordBank\LandlordBank;
use Spatie\Multitenancy\Models\Tenant;

class LandlordBankObserver
{
    /**
     * Handle the LandlordBank "created" event.
     */
    public function created(LandlordBank $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'banks', 'Banka Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordBank "updated" event.
     */
    public function updated(LandlordBank $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'banks', 'Banka Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordBank "deleted" event.
     */
    public function deleted(LandlordBank $model): void
    {
        //
    }

    /**
     * Handle the LandlordBank "restored" event.
     */
    public function restored(LandlordBank $model): void
    {
        //
    }

    /**
     * Handle the LandlordBank "force deleted" event.
     */
    public function forceDeleted(LandlordBank $model): void
    {
        //
    }
}

