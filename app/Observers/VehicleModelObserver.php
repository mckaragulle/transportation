<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\VehicleModel;
use Spatie\Multitenancy\Models\Tenant;

class VehicleModelObserver
{
    /**
     * Handle the VehicleModel "created" event.
     */
    public function created(VehicleModel $vehicleModel): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($vehicleModel) {
            $data = getTenantSyncDataJob($vehicleModel);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_models', 'Araç Tipi Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the VehicleModel "updated" event.
     */
    public function updated(VehicleModel $vehicleModel): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($vehicleModel) {
            $data = getTenantSyncDataJob($vehicleModel);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_models', 'Araç Tipi Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the VehicleModel "deleted" event.
     */
    public function deleted(VehicleModel $vehicleModel): void
    {
        //
    }

    /**
     * Handle the VehicleModel "restored" event.
     */
    public function restored(VehicleModel $vehicleModel): void
    {
        //
    }

    /**
     * Handle the VehicleModel "force deleted" event.
     */
    public function forceDeleted(VehicleModel $vehicleModel): void
    {
        //
    }
}
