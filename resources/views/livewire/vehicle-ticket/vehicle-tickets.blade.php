<div>
    @can('create vehicle_tickets')
    <livewire:vehicle-ticket.vehicle-ticket-create />
    <hr>
    @endcan
    @can('read vehicle_tickets')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Araba Tipleri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:vehicle-ticket.vehicle-ticket-table />
            </div>
        </div>
    </div>
    @endcan
</div>