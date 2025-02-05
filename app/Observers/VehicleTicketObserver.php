<?php

namespace App\Observers;

use App\Jobs\Tenant\TenantSyncDataJob;
use App\Models\VehicleTicket;
use Spatie\Multitenancy\Models\Tenant;

class VehicleTicketObserver
{
    /**
     * Handle the VehicleTicket "created" event.
     */
    public function created(VehicleTicket $vehicleTicket): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($vehicleTicket) {
            $data = getTenantSyncDataJob($vehicleTicket);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_tickets', 'Araç Modeli Eklenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the VehicleTicket "updated" event.
     */
    public function updated(VehicleTicket $vehicleTicket): void
    {
        Tenant::all()->eachCurrent(function(Tenant $tenant) use ($vehicleTicket) {
            $data = getTenantSyncDataJob($vehicleTicket);
            TenantSyncDataJob::dispatch($tenant->id, $data['id'], $data['data'], 'vehicle_tickets', 'Araç Modeli Güncellenirken Hata Oluştu.');
        });
    }

    /**
     * Handle the VehicleTicket "deleted" event.
     */
    public function deleted(VehicleTicket $vehicleTicket): void
    {
        //
    }

    /**
     * Handle the VehicleTicket "restored" event.
     */
    public function restored(VehicleTicket $vehicleTicket): void
    {
        //
    }

    /**
     * Handle the VehicleTicket "force deleted" event.
     */
    public function forceDeleted(VehicleTicket $vehicleTicket): void
    {
        //
    }
}
