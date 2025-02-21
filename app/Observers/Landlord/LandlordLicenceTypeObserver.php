<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordLicenceType;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordLicenceTypeObserver
{
    /**
     * Handle the LandlordLicenceType "created" event.
     */
    public function created(LandlordLicenceType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_types', 'Sürücü Belgesi Seçeneği Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLicenceType "updated" event.
     */
    public function updated(LandlordLicenceType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'licence_types', 'Sürücü Belgesi Seçeneği Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLicenceType "deleted" event.
     */
    public function deleted(LandlordLicenceType $model): void
    {
        //
    }

    /**
     * Handle the LandlordLicenceType "restored" event.
     */
    public function restored(LandlordLicenceType $model): void
    {
        //
    }

    /**
     * Handle the LandlordLicenceType "force deleted" event.
     */
    public function forceDeleted(LandlordLicenceType $model): void
    {
        //
    }
}

