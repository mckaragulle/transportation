<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordAccountGroup;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordAccountGroupObserver
{
    /**
     * Handle the LandlordAccountGroup "created" event.
     */
    public function created(LandlordAccountGroup $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'account_group', 'Cari Grubu Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordAccountGroup "updated" event.
     */
    public function updated(LandlordAccountGroup $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'account_group', 'Cari Grubu Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordAccountGroup "deleted" event.
     */
    public function deleted(LandlordAccountGroup $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountGroup "restored" event.
     */
    public function restored(LandlordAccountGroup $model): void
    {
        //
    }

    /**
     * Handle the LandlordAccountGroup "force deleted" event.
     */
    public function forceDeleted(LandlordAccountGroup $model): void
    {
        //
    }
}

