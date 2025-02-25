<div>
    @can('create vehicle_properties')
    <livewire:landlord.vehicle-property.vehicle-property-create />
    <hr>
    @endcan
    @can('read vehicle_properties')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Araç Özellikleri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.vehicle-property.vehicle-property-table />
            </div>
        </div>
    </div>
    @endcan
</div>
