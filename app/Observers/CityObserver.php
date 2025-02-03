<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantCityJob;
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
            TenantCityJob::dispatch($tenant->id, $city);
        });

    }

    /**
     * Handle the City "updated" event.
     */
    public function updated(City $city): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($city) {
            TenantCityJob::dispatch($tenant->id, $city);
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
