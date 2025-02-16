<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordAccountBank;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordAccountBankObserver
{
    /**
     * Handle the LandlordAccountBank "created" event.
     */
    public function created(LandlordAccountBank $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordAccountBank "updated" event.
     */
    public function updated(LandlordAccountBank $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordAccountBank "deleted" event.
     */
    public function deleted(LandlordAccountBank $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountBank "restored" event.
     */
    public function restored(LandlordAccountBank $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountBank "force deleted" event.
     */
    public function forceDeleted(LandlordAccountBank $model): void
    {
        //
    }
}

