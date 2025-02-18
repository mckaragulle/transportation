<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordVehicleBrand;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordVehicleBrandObserver
{
    /**
     * Handle the LandlordVehicleBrand "created" event.
     */
    public function created(LandlordVehicleBrand $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_brands', 'Araç Markası Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehicleBrand "updated" event.
     */
    public function updated(LandlordVehicleBrand $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_brands', 'Araç Markası Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehicleBrand "deleted" event.
     */
    public function deleted(LandlordVehicleBrand $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehicleBrand "restored" event.
     */
    public function restored(LandlordVehicleBrand $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehicleBrand "force deleted" event.
     */
    public function forceDeleted(LandlordVehicleBrand $model): void
    {
        //
    }
}

