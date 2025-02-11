<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\DealerType;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class DealerTypeObserver
{
    /**
     * Handle the DealerType "created" event.
     */
    public function created(DealerType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the DealerType "updated" event.
     */
    public function updated(DealerType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the DealerType "deleted" event.
     */
    public function deleted(DealerType $model): void
    {
        //
    }

    /**
     * Handle the DealerType "restored" event.
     */
    public function restored(DealerType $model): void
    {
        //
    }

    /**
     * Handle the DealerType "force deleted" event.
     */
    public function forceDeleted(DealerType $model): void
    {
        //
    }
}

