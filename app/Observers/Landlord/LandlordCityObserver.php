<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordCity;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordCityObserver
{
    /**
     * Handle the LandlordCity "created" event.
     */
    public function created(LandlordCity $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'cities', 'Şehir Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordCity "updated" event.
     */
    public function updated(LandlordCity $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'cities', 'Şehir Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordCity "deleted" event.
     */
    public function deleted(LandlordCity $model): void
    {
        //
    }

    /**
     * Handle the LandlordCity "restored" event.
     */
    public function restored(LandlordCity $model): void
    {
        //
    }

    /**
     * Handle the LandlordCity "force deleted" event.
     */
    public function forceDeleted(LandlordCity $model): void
    {
        //
    }
}

