<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantCityJob;
use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\City;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Spatie\Multitenancy\Models\Tenant;

class CityObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the City "created" event.
     */
    public function created(City $city): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($city) {
            $data = getTenantSyncDataJob($city);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'cities', 'Şehir Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the City "updated" event.
     */
    public function updated(City $city): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($city) {
            $data = getTenantSyncDataJob($city);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'cities', 'Şehir Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the City "deleted" event.
     */
    public function deleted(City $city): void
    {

    }

    /**
     * Handle the City "restored" event.
     */
    public function restored(City $city): void
    {
        //
    }

    /**
     * Handle the City "force deleted" event.
     */
    public function forceDeleted(City $city): void
    {
        //
    }
}
