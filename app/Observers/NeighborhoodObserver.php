<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantNeighborhoodJob;
use App\Models\Neighborhood;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Spatie\Multitenancy\Models\Tenant;

class NeighborhoodObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Neighborhood "created" event.
     */
    public function created(Neighborhood $neighborhood): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($neighborhood) {
            TenantNeighborhoodJob::dispatch($tenant->id, $neighborhood);
        });
    }

    /**
     * Handle the Neighborhood "updated" event.
     */
    public function updated(Neighborhood $neighborhood): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($neighborhood) {
            TenantNeighborhoodJob::dispatch($tenant->id, $neighborhood);
        });
    }

    /**
     * Handle the Neighborhood "deleted" event.
     */
    public function deleted(Neighborhood $neighborhood): void
    {

    }

    /**
     * Handle the Neighborhood "restored" event.
     */
    public function restored(Neighborhood $neighborhood): void
    {
        //
    }

    /**
     * Handle the Neighborhood "force deleted" event.
     */
    public function forceDeleted(Neighborhood $neighborhood): void
    {
        //
    }
}
