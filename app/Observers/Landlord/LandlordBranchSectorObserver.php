<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordBranchSector;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordBranchSectorObserver
{
    /**
     * Handle the LandlordBranchSector "created" event.
     */
    public function created(LandlordBranchSector $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'branch_sectors', 'Şube Sektörleri Güncellenirken Hata Oluştu');
        });
    }

    /**
     * Handle the LandlordBranchSector "updated" event.
     */
    public function updated(LandlordBranchSector $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'branch_sectors', 'Şube Sektörleri Güncellenirken Hata Oluştu');
        });
    }

    /**
     * Handle the LandlordBranchSector "deleted" event.
     */
    public function deleted(LandlordBranchSector $model): void
    {
        //
    }

    /**
     * Handle the LandlordBranchSector "restored" event.
     */
    public function restored(LandlordBranchSector $model): void
    {
        //
    }

    /**
     * Handle the LandlordBranchSector "force deleted" event.
     */
    public function forceDeleted(LandlordBranchSector $model): void
    {
        //
    }
}

