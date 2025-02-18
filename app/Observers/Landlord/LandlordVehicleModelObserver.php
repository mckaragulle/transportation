<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordVehicleModel;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordVehicleModelObserver
{
    /**
     * Handle the LandlordVehicleModel "created" event.
     */
    public function created(LandlordVehicleModel $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_models', 'Araç Modeli Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehicleModel "updated" event.
     */
    public function updated(LandlordVehicleModel $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_models', 'Araç Modeli Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehicleModel "deleted" event.
     */
    public function deleted(LandlordVehicleModel $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehicleModel "restored" event.
     */
    public function restored(LandlordVehicleModel $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehicleModel "force deleted" event.
     */
    public function forceDeleted(LandlordVehicleModel $model): void
    {
        //
    }
}

