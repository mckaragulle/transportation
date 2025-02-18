<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordStaffType;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordStaffTypeObserver
{
    /**
     * Handle the LandlordStaffType "created" event.
     */
    public function created(LandlordStaffType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordStaffType "updated" event.
     */
    public function updated(LandlordStaffType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordStaffType "deleted" event.
     */
    public function deleted(LandlordStaffType $model): void
    {
        //
    }

    /**
     * Handle the LandlordStaffType "restored" event.
     */
    public function restored(LandlordStaffType $model): void
    {
        //
    }

    /**
     * Handle the LandlordStaffType "force deleted" event.
     */
    public function forceDeleted(LandlordStaffType $model): void
    {
        //
    }
}

