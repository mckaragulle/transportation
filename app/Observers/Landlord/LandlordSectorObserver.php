<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordSector;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordSectorObserver
{
    /**
     * Handle the LandlordSector "created" event.
     */
    public function created(LandlordSector $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordSector "updated" event.
     */
    public function updated(LandlordSector $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordSector "deleted" event.
     */
    public function deleted(LandlordSector $model): void
    {
        //
    }

    /**
     * Handle the LandlordSector "restored" event.
     */
    public function restored(LandlordSector $model): void
    {
        //
    }

    /**
     * Handle the LandlordSector "force deleted" event.
     */
    public function forceDeleted(LandlordSector $model): void
    {
        //
    }
}

