<div>
    @can('create hgs_types')
    <livewire:landlord.hgs-type.hgs-type-create />
    <hr>
    @endcan
    @can('read hgs_types')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">HGSLER</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.hgs-type.hgs-type-table />
            </div>
        </div>
    </div>
    @endcan
</div>
