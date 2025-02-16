<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordAccountAddress;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordAccountAddressObserver
{
    /**
     * Handle the LandlordAccountAddress "created" event.
     */
    public function created(LandlordAccountAddress $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordAccountAddress "updated" event.
     */
    public function updated(LandlordAccountAddress $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordAccountAddress "deleted" event.
     */
    public function deleted(LandlordAccountAddress $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountAddress "restored" event.
     */
    public function restored(LandlordAccountAddress $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountAddress "force deleted" event.
     */
    public function forceDeleted(LandlordAccountAddress $model): void
    {
        //
    }
}

