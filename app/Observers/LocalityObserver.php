<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantLocalityJob;
use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\Locality;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Spatie\Multitenancy\Models\Tenant;

class LocalityObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Locality "created" event.
     */
    public function created(Locality $locality): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($locality) {
            $data = getTenantSyncDataJob($locality);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'localities', 'Mahalle Eklenirken Hata Oluştu.')
                ->delay(now()->addMinutes(10));
        });

    }

    /**
     * Handle the Locality "updated" event.
     */
    public function updated(Locality $locality): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($locality) {
            $data = getTenantSyncDataJob($locality);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'localities', 'Mahalle Güncellenirken Hata Oluştu.')
                ->delay(now()->addMinutes(10));
        });
    }

    /**
     * Handle the Locality "deleted" event.
     */
    public function deleted(Locality $locality): void
    {

    }

    /**
     * Handle the Locality "restored" event.
     */
    public function restored(Locality $locality): void
    {
        //
    }

    /**
     * Handle the Locality "force deleted" event.
     */
    public function forceDeleted(Locality $locality): void
    {
        //
    }
}
