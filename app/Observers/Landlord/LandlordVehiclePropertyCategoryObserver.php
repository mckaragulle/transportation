<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordVehiclePropertyCategory;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordVehiclePropertyCategoryObserver
{
    /**
     * Handle the LandlordVehiclePropertyCategory "created" event.
     */
    public function created(LandlordVehiclePropertyCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_property_categories', 'Araç Kategorisi Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehiclePropertyCategory "updated" event.
     */
    public function updated(LandlordVehiclePropertyCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_property_categories', 'Araç Kategorisi Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehiclePropertyCategory "deleted" event.
     */
    public function deleted(LandlordVehiclePropertyCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehiclePropertyCategory "restored" event.
     */
    public function restored(LandlordVehiclePropertyCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehiclePropertyCategory "force deleted" event.
     */
    public function forceDeleted(LandlordVehiclePropertyCategory $model): void
    {
        //
    }
}

