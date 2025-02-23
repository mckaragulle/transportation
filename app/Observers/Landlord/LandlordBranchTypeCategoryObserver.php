<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordBranchTypeCategory;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordBranchTypeCategoryObserver
{
    /**
     * Handle the LandlordBranchTypeCategory "created" event.
     */
    public function created(LandlordBranchTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'branch_type_categories', 'Şube kategorisi eklenirken hata oluştu.');
        });
    }

    /**
     * Handle the LandlordBranchTypeCategory "updated" event.
     */
    public function updated(LandlordBranchTypeCategory $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'branch_type_categories', 'Şube kategorisi güncellenirken hata oluştu.');
        });
    }

    /**
     * Handle the LandlordBranchTypeCategory "deleted" event.
     */
    public function deleted(LandlordBranchTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordBranchTypeCategory "restored" event.
     */
    public function restored(LandlordBranchTypeCategory $model): void
    {
        //
    }

    /**
     * Handle the LandlordBranchTypeCategory "force deleted" event.
     */
    public function forceDeleted(LandlordBranchTypeCategory $model): void
    {
        //
    }
}

