<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordGroup;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordGroupObserver
{
    /**
     * Handle the LandlordGroup "created" event.
     */
    public function created(LandlordGroup $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordGroup "updated" event.
     */
    public function updated(LandlordGroup $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordGroup "deleted" event.
     */
    public function deleted(LandlordGroup $model): void
    {
        //
    }

    /**
     * Handle the LandlordGroup "restored" event.
     */
    public function restored(LandlordGroup $model): void
    {
        //
    }

    /**
     * Handle the LandlordGroup "force deleted" event.
     */
    public function forceDeleted(LandlordGroup $model): void
    {
        //
    }
}

