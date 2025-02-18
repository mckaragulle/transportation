<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordStaffTypeCategory;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordStaffTypeCategoryObserver
{
    /**
     * Handle the LandlordStaffTypeCategory "created" event.
     */
    public function created(LandlordStaffTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordStaffTypeCategory "updated" event.
     */
    public function updated(LandlordStaffTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the LandlordStaffTypeCategory "deleted" event.
     */
    public function deleted(LandlordStaffTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordStaffTypeCategory "restored" event.
     */
    public function restored(LandlordStaffTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordStaffTypeCategory "force deleted" event.
     */
    public function forceDeleted(LandlordStaffTypeCategory $model): void
    {
        //
    }
}

