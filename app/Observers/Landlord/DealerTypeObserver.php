<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordDealerType;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordDealerTypeObserver
{
    /**
     * Handle the DealerType "created" event.
     */
    public function created(LandlordDealerType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the DealerType "updated" event.
     */
    public function updated(LandlordDealerType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the DealerType "deleted" event.
     */
    public function deleted(LandlordDealerType $model): void
    {
        //
    }

    /**
     * Handle the DealerType "restored" event.
     */
    public function restored(LandlordDealerType $model): void
    {
        //
    }

    /**
     * Handle the DealerType "force deleted" event.
     */
    public function forceDeleted(LandlordDealerType $model): void
    {
        //
    }
}

