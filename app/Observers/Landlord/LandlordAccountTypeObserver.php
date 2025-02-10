<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordAccountType;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordAccountTypeObserver
{
    /**
     * Handle the LandlordAccountType "created" event.
     */
    public function created(LandlordAccountType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'account_types', 'Cari Seçeneği Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordAccountType "updated" event.
     */
    public function updated(LandlordAccountType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'account_types', 'Cari Seçeneği Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordAccountType "deleted" event.
     */
    public function deleted(LandlordAccountType $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountType "restored" event.
     */
    public function restored(LandlordAccountType $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountType "force deleted" event.
     */
    public function forceDeleted(LandlordAccountType $model): void
    {
        //
    }
}

