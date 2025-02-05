<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\VehicleBrand;
use Spatie\Multitenancy\Models\Tenant;

class VehicleBrandObserver
{
    /**
     * Handle the VehicleBrand "created" event.
     */
    public function created(VehicleBrand $vehicleBrand): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($vehicleBrand) {
            $data = getTenantSyncDataJob($vehicleBrand);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_brands', 'Araç Markası Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the VehicleBrand "updated" event.
     */
    public function updated(VehicleBrand $vehicleBrand): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($vehicleBrand) {
            $data = getTenantSyncDataJob($vehicleBrand);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_brands', 'Araç Markası Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the VehicleBrand "deleted" event.
     */
    public function deleted(VehicleBrand $vehicleBrand): void
    {
        //
    }

    /**
     * Handle the VehicleBrand "restored" event.
     */
    public function restored(VehicleBrand $vehicleBrand): void
    {
        //
    }

    /**
     * Handle the VehicleBrand "force deleted" event.
     */
    public function forceDeleted(VehicleBrand $vehicleBrand): void
    {
        //
    }
}
