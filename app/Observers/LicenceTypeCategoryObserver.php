<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\Tenant\LicenceTypeCategory;
use Spatie\Multitenancy\Models\Tenant;

class LicenceTypeCategoryObserver
{
    /**
     * Handle the LicenceTypeCategory "created" event.
     */
    public function created(LicenceTypeCategory $licenceTypeCategory): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($licenceTypeCategory) {
            $data = getTenantSyncDataJob($licenceTypeCategory);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_type_categories', 'Sürücü Belge Kategorisi Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LicenceTypeCategory "updated" event.
     */
    public function updated(LicenceTypeCategory $licenceTypeCategory): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($licenceTypeCategory) {
            $data = getTenantSyncDataJob($licenceTypeCategory);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_type_categories', 'Sürücü Belge Kategorisi Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LicenceTypeCategory "deleted" event.
     */
    public function deleted(LicenceTypeCategory $licenceTypeCategory): void
    {
        //
    }

    /**
     * Handle the LicenceTypeCategory "restored" event.
     */
    public function restored(LicenceTypeCategory $licenceTypeCategory): void
    {
        //
    }

    /**
     * Handle the LicenceTypeCategory "force deleted" event.
     */
    public function forceDeleted(LicenceTypeCategory $licenceTypeCategory): void
    {
        //
    }
}
