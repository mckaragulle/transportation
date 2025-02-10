<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\Landlord\LandlordLicenceTypeCategory;
use Spatie\Multitenancy\Models\Tenant;

class LandlordLicenceTypeCategoryObserver
{
    /**
     * Handle the LandlordLicenceTypeCategory "created" event.
     */
    public function created(LandlordLicenceTypeCategory $landlordLicenceTypeCategory): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($landlordLicenceTypeCategory) {
            $data = getTenantSyncDataJob($landlordLicenceTypeCategory);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_type_categories', 'Sürücü Belge Kategorisi Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLicenceTypeCategory "updated" event.
     */
    public function updated(LandlordLicenceTypeCategory $landlordLicenceTypeCategory): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($landlordLicenceTypeCategory) {
            $data = getTenantSyncDataJob($landlordLicenceTypeCategory);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_type_categories', 'Sürücü Belge Kategorisi Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLicenceTypeCategory "deleted" event.
     */
    public function deleted(LandlordLicenceTypeCategory $landlordLicenceTypeCategory): void
    {
        //
    }

    /**
     * Handle the LandlordLicenceTypeCategory "restored" event.
     */
    public function restored(LandlordLicenceTypeCategory $landlordLicenceTypeCategory): void
    {
        //
    }

    /**
     * Handle the LandlordLicenceTypeCategory "force deleted" event.
     */
    public function forceDeleted(LandlordLicenceTypeCategory $landlordLicenceTypeCategory): void
    {
        //
    }
}
