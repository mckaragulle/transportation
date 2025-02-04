<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantDistrictJob;
use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\District;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Spatie\Multitenancy\Models\Tenant;

class DistrictObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the District "created" event.
     */
    public function created(District $district): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($district) {
            $data = getTenantSyncDataJob($district);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'districts', 'İlçe Eklenirken Hata Oluştu.')
                ->delay(now()->addMinutes(5));
        });

    }

    /**
     * Handle the District "updated" event.
     */
    public function updated(District $district): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($district) {
            $data = getTenantSyncDataJob($district);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'districts', 'İlçe Güncellenirken Hata Oluştu.')
                ->delay(now()->addMinutes(5));
        });
    }

    /**
     * Handle the District "deleted" event.
     */
    public function deleted(District $district): void
    {

    }

    /**
     * Handle the District "restored" event.
     */
    public function restored(District $district): void
    {
        //
    }

    /**
     * Handle the District "force deleted" event.
     */
    public function forceDeleted(District $district): void
    {
        //
    }
}
