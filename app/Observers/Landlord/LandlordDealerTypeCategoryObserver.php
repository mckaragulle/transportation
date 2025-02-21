<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordDealerTypeCategory;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordDealerTypeCategoryObserver
{
    /**
     * Handle the LandlordDealerTypeCategory "created" event.
     */
    public function created(LandlordDealerTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'dealer_type_categories', 'Bayi Kategorisi Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordDealerTypeCategory "updated" event.
     */
    public function updated(LandlordDealerTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'dealer_type_categories', 'Bayi Kategorisi Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordDealerTypeCategory "deleted" event.
     */
    public function deleted(LandlordDealerTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordDealerTypeCategory "restored" event.
     */
    public function restored(LandlordDealerTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordDealerTypeCategory "force deleted" event.
     */
    public function forceDeleted(LandlordDealerTypeCategory $model): void
    {
        //
    }
}

