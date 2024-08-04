<div>
    @can('create vehicleTickets')
    <livewire:vehicle-ticket.vehicle-ticket-create />
    <hr>
    @endcan
    @can('read vehicleTickets')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Araba Modelleri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:vehicle-ticket.vehicle-ticket-table />
            </div>
        </div>
    </div>
    @endcan
</div>