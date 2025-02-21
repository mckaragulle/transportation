<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordVehicleProperty;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordVehiclePropertyObserver
{
    /**
     * Handle the LandlordVehicleProperty "created" event.
     */
    public function created(LandlordVehicleProperty $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_properties', 'Araç Kategori Seçeneği Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehicleProperty "updated" event.
     */
    public function updated(LandlordVehicleProperty $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_properties', 'Araç Kategori Seçeneği Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehicleProperty "deleted" event.
     */
    public function deleted(LandlordVehicleProperty $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehicleProperty "restored" event.
     */
    public function restored(LandlordVehicleProperty $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehicleProperty "force deleted" event.
     */
    public function forceDeleted(LandlordVehicleProperty $model): void
    {
        //
    }
}

