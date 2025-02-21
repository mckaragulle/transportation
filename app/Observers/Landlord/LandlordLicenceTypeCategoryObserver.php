<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordLicenceTypeCategory;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordLicenceTypeCategoryObserver
{
    /**
     * Handle the LandlordLicenceTypeCategory "created" event.
     */
    public function created(LandlordLicenceTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_type_categories', 'Sürücü Belgesi Kategorisi Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLicenceTypeCategory "updated" event.
     */
    public function updated(LandlordLicenceTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_type_categories', 'Sürücü Belgesi Kategorisi Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLicenceTypeCategory "deleted" event.
     */
    public function deleted(LandlordLicenceTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordLicenceTypeCategory "restored" event.
     */
    public function restored(LandlordLicenceTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordLicenceTypeCategory "force deleted" event.
     */
    public function forceDeleted(LandlordLicenceTypeCategory $model): void
    {
        //
    }
}

