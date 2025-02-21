<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordDealerOfficer;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordDealerOfficerObserver
{
    /**
     * Handle the LandlordDealerOfficer "created" event.
     */
    public function created(LandlordDealerOfficer $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordDealerOfficer "updated" event.
     */
    public function updated(LandlordDealerOfficer $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordDealerOfficer "deleted" event.
     */
    public function deleted(LandlordDealerOfficer $model): void
    {
        //
    }

    /**
     * Handle the LandlordDealerOfficer "restored" event.
     */
    public function restored(LandlordDealerOfficer $model): void
    {
        //
    }

    /**
     * Handle the LandlordDealerOfficer "force deleted" event.
     */
    public function forceDeleted(LandlordDealerOfficer $model): void
    {
        //
    }
}

