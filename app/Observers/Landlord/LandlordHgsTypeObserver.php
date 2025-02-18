<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordHgsType;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordHgsTypeObserver
{
    /**
     * Handle the LandlordHgsType "created" event.
     */
    public function created(LandlordHgsType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordHgsType "updated" event.
     */
    public function updated(LandlordHgsType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordHgsType "deleted" event.
     */
    public function deleted(LandlordHgsType $model): void
    {
        //
    }

    /**
     * Handle the LandlordHgsType "restored" event.
     */
    public function restored(LandlordHgsType $model): void
    {
        //
    }

    /**
     * Handle the LandlordHgsType "force deleted" event.
     */
    public function forceDeleted(LandlordHgsType $model): void
    {
        //
    }
}

