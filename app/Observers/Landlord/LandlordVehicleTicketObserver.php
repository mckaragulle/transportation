<?php

namespace App\Observers\Landlord;

use App\Models\Landlord\LandlordVehicleTicket;
use App\Jobs\Tenant\TenantSyncDataJob;
use Spatie\Multitenancy\Models\Tenant;

class LandlordVehicleTicketObserver
{
    /**
     * Handle the LandlordVehicleTicket "created" event.
     */
    public function created(LandlordVehicleTicket $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_tickets', 'Araç Tipi Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehicleTicket "updated" event.
     */
    public function updated(LandlordVehicleTicket $model): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($model) {
            $data = getTenantSyncDataJob($model);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_tickets', 'Araç Tipi Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the LandlordVehicleTicket "deleted" event.
     */
    public function deleted(LandlordVehicleTicket $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehicleTicket "restored" event.
     */
    public function restored(LandlordVehicleTicket $model): void
    {
        //
    }

    /**
     * Handle the LandlordVehicleTicket "force deleted" event.
     */
    public function forceDeleted(LandlordVehicleTicket $model): void
    {
        //
    }
}

