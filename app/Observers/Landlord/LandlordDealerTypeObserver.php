<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordDealerType;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordDealerTypeObserver
{
    /**
     * Handle the LandlordDealerType "created" event.
     */
    public function created(LandlordDealerType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'dealer_types', 'Bayi Seçeneği Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordDealerType "updated" event.
     */
    public function updated(LandlordDealerType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'dealer_types', 'Bayi Seçeneği Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordDealerType "deleted" event.
     */
    public function deleted(LandlordDealerType $model): void
    {
        //
    }

    /**
     * Handle the LandlordDealerType "restored" event.
     */
    public function restored(LandlordDealerType $model): void
    {
        //
    }

    /**
     * Handle the LandlordDealerType "force deleted" event.
     */
    public function forceDeleted(LandlordDealerType $model): void
    {
        //
    }
}

