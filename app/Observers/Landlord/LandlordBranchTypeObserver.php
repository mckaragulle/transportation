<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordBranchType;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordBranchTypeObserver
{
    /**
     * Handle the LandlordBranchType "created" event.
     */
    public function created(LandlordBranchType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'branch_types', 'Şube kategori seçeneği eklenirken hata oluştu.');
        });
    }

    /**
     * Handle the LandlordBranchType "updated" event.
     */
    public function updated(LandlordBranchType $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'branch_types', 'Şube kategori seçeneği güncellenirken hata oluştu.');
        });
    }

    /**
     * Handle the LandlordBranchType "deleted" event.
     */
    public function deleted(LandlordBranchType $model): void
    {
        //
    }

    /**
     * Handle the LandlordBranchType "restored" event.
     */
    public function restored(LandlordBranchType $model): void
    {
        //
    }

    /**
     * Handle the LandlordBranchType "force deleted" event.
     */
    public function forceDeleted(LandlordBranchType $model): void
    {
        //
    }
}

