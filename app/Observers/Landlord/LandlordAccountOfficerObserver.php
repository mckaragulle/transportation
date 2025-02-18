<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordAccountOfficer;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordAccountOfficerObserver
{
    /**
     * Handle the LandlordAccountOfficer "created" event.
     */
    public function created(LandlordAccountOfficer $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordAccountOfficer "updated" event.
     */
    public function updated(LandlordAccountOfficer $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordAccountOfficer "deleted" event.
     */
    public function deleted(LandlordAccountOfficer $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountOfficer "restored" event.
     */
    public function restored(LandlordAccountOfficer $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountOfficer "force deleted" event.
     */
    public function forceDeleted(LandlordAccountOfficer $model): void
    {
        //
    }
}

