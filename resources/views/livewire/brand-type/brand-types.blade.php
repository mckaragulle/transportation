<div>
    @can('create brandTypes')
    <livewire:brand-type.brand-type-create />
    <hr>
    @endcan
    @can('read brands')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Marka Tipleri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:brand-type.brand-type-table />
            </div>
        </div>
    </div>
    @endcan
</div>
