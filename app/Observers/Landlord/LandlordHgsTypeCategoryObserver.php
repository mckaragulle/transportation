<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordHgsTypeCategory;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordHgsTypeCategoryObserver
{
    /**
     * Handle the LandlordHgsTypeCategory "created" event.
     */
    public function created(LandlordHgsTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'hgs_type_categories', 'Hgs Kategorisi Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordHgsTypeCategory "updated" event.
     */
    public function updated(LandlordHgsTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'hgs_type_categories', 'Hgs Kategorisi Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordHgsTypeCategory "deleted" event.
     */
    public function deleted(LandlordHgsTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordHgsTypeCategory "restored" event.
     */
    public function restored(LandlordHgsTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordHgsTypeCategory "force deleted" event.
     */
    public function forceDeleted(LandlordHgsTypeCategory $model): void
    {
        //
    }
}

