<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\Landlord\LandlordLicenceType;
use Spatie\Multitenancy\Models\Tenant;

class LandlordLicenceTypeObserver
{
    /**
     * Handle the LandlordLicenceType "created" event.
     */
    public function created(LandlordLicenceType $landlordLicenceType): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($landlordLicenceType) {
            $data = getTenantSyncDataJob($landlordLicenceType);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_types', 'Sürücü Belge Seçeneği Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLicenceType "updated" event.
     */
    public function updated(LandlordLicenceType $landlordLicenceType): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($landlordLicenceType) {
            $data = getTenantSyncDataJob($landlordLicenceType);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_type', 'Sürücü Belge Seçeneği Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLicenceType "deleted" event.
     */
    public function deleted(LandlordLicenceType $landlordLicenceType): void
    {
        //
    }

    /**
     * Handle the LandlordLicenceType "restored" event.
     */
    public function restored(LandlordLicenceType $landlordLicenceType): void
    {
        //
    }

    /**
     * Handle the LandlordLicenceType "force deleted" event.
     */
    public function forceDeleted(LandlordLicenceType $landlordLicenceType): void
    {
        //
    }
}
