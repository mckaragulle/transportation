<?php

namespace App\Observers\Tenant;

use App\Models\Tenant\AccountTypeCategory;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class AccountTypeCategoryObserver
{
    /**
     * Handle the AccountTypeCategory "created" event.
     */
    public function created(AccountTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the AccountTypeCategory "updated" event.
     */
    public function updated(AccountTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'table', 'mesaj');
        });
    }

    /**
     * Handle the AccountTypeCategory "deleted" event.
     */
    public function deleted(AccountTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the AccountTypeCategory "restored" event.
     */
    public function restored(AccountTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the AccountTypeCategory "force deleted" event.
     */
    public function forceDeleted(AccountTypeCategory $model): void
    {
        //
    }
}

