<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordBranchGroup;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordBranchGroupObserver
{
    /**
     * Handle the LandlordBranchGroup "created" event.
     */
    public function created(LandlordBranchGroup $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'branch_groups', 'Şube Grupları Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordBranchGroup "updated" event.
     */
    public function updated(LandlordBranchGroup $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'branch_groups', 'Şube Grupları Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordBranchGroup "deleted" event.
     */
    public function deleted(LandlordBranchGroup $model): void
    {
        //
    }

    /**
     * Handle the LandlordBranchGroup "restored" event.
     */
    public function restored(LandlordBranchGroup $model): void
    {
        //
    }

    /**
     * Handle the LandlordBranchGroup "force deleted" event.
     */
    public function forceDeleted(LandlordBranchGroup $model): void
    {
        //
    }
}

