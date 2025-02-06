<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\Tenant\LicenceType;
use Spatie\Multitenancy\Models\Tenant;

class LicenceTypeObserver
{
    /**
     * Handle the LicenceType "created" event.
     */
    public function created(LicenceType $licenceType): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($licenceType) {
            $data = getTenantSyncDataJob($licenceType);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_types', 'Sürücü Belge Seçeneği Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LicenceType "updated" event.
     */
    public function updated(LicenceType $licenceType): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($licenceType) {
            $data = getTenantSyncDataJob($licenceType);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_type', 'Sürücü Belge Seçeneği Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LicenceType "deleted" event.
     */
    public function deleted(LicenceType $licenceType): void
    {
        //
    }

    /**
     * Handle the LicenceType "restored" event.
     */
    public function restored(LicenceType $licenceType): void
    {
        //
    }

    /**
     * Handle the LicenceType "force deleted" event.
     */
    public function forceDeleted(LicenceType $licenceType): void
    {
        //
    }
}
