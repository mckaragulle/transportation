<div>
    @can('create vehicleModels')
    <livewire:vehicle-model.vehicle-model-create />
    <hr>
    @endcan
    @can('read vehicleModels')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Araba Modelleri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:vehicle-model.vehicle-model-table />
            </div>
        </div>
    </div>
    @endcan
</div>