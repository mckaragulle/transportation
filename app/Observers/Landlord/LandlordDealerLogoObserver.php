<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordDealerLogo;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordDealerLogoObserver
{
    /**
     * Handle the LandlordDealerLogo "created" event.
     */
    public function created(LandlordDealerLogo $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordDealerLogo "updated" event.
     */
    public function updated(LandlordDealerLogo $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordDealerLogo "deleted" event.
     */
    public function deleted(LandlordDealerLogo $model): void
    {
        //
    }

    /**
     * Handle the LandlordDealerLogo "restored" event.
     */
    public function restored(LandlordDealerLogo $model): void
    {
        //
    }

    /**
     * Handle the LandlordDealerLogo "force deleted" event.
     */
    public function forceDeleted(LandlordDealerLogo $model): void
    {
        //
    }
}

