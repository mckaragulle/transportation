<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordDistrict;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordDistrictObserver
{
    /**
     * Handle the LandlordDistrict "created" event.
     */
    public function created(LandlordDistrict $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'districts', 'İlçe Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordDistrict "updated" event.
     */
    public function updated(LandlordDistrict $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'districts', 'İlçe Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordDistrict "deleted" event.
     */
    public function deleted(LandlordDistrict $model): void
    {
        //
    }

    /**
     * Handle the LandlordDistrict "restored" event.
     */
    public function restored(LandlordDistrict $model): void
    {
        //
    }

    /**
     * Handle the LandlordDistrict "force deleted" event.
     */
    public function forceDeleted(LandlordDistrict $model): void
    {
        //
    }
}

