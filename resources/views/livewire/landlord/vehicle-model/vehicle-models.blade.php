<div>
    @can('create vehicle_models')
    <livewire:landlord.vehicle-model.vehicle-model-create />
    <hr>
    @endcan
    @can('read vehicle_models')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Araba Modelleri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.vehicle-model.vehicle-model-table />
            </div>
        </div>
    </div>
    @endcan
</div>
