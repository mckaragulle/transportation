<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordNeighborhood;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordNeighborhoodObserver
{
    /**
     * Handle the LandlordNeighborhood "created" event.
     */
    public function created(LandlordNeighborhood $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordNeighborhood "updated" event.
     */
    public function updated(LandlordNeighborhood $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordNeighborhood "deleted" event.
     */
    public function deleted(LandlordNeighborhood $model): void
    {
        //
    }

    /**
     * Handle the LandlordNeighborhood "restored" event.
     */
    public function restored(LandlordNeighborhood $model): void
    {
        //
    }

    /**
     * Handle the LandlordNeighborhood "force deleted" event.
     */
    public function forceDeleted(LandlordNeighborhood $model): void
    {
        //
    }
}

