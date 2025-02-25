<div>
    @can('create vehicle_brands')
    <livewire:landlord.vehicle-brand.vehicle-brand-create />
    <hr>
    @endcan
    @can('read vehicle_brands')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Araç Markaları</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.vehicle-brand.vehicle-brand-table />
            </div>
        </div>
    </div>
    @endcan
</div>
