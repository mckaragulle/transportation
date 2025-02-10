<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordAccountTypeCategory;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordAccountTypeCategoryObserver
{
    /**
     * Handle the LandlordAccountTypeCategory "created" event.
     */
    public function created(LandlordAccountTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'account_type_categories', 'Cari Kategorisi Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordAccountTypeCategory "updated" event.
     */
    public function updated(LandlordAccountTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'account_type_categories', 'Cari Kategorisi Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordAccountTypeCategory "deleted" event.
     */
    public function deleted(LandlordAccountTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountTypeCategory "restored" event.
     */
    public function restored(LandlordAccountTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountTypeCategory "force deleted" event.
     */
    public function forceDeleted(LandlordAccountTypeCategory $model): void
    {
        //
    }
}

