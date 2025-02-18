<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordLocality;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordLocalityObserver
{
    /**
     * Handle the LandlordLocality "created" event.
     */
    public function created(LandlordLocality $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'localities', 'Semt Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLocality "updated" event.
     */
    public function updated(LandlordLocality $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'localities', 'Semt Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordLocality "deleted" event.
     */
    public function deleted(LandlordLocality $model): void
    {
        //
    }

    /**
     * Handle the LandlordLocality "restored" event.
     */
    public function restored(LandlordLocality $model): void
    {
        //
    }

    /**
     * Handle the LandlordLocality "force deleted" event.
     */
    public function forceDeleted(LandlordLocality $model): void
    {
        //
    }
}

