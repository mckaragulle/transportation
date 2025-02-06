<div>
    @can('create licence_types')
    <livewire:tenant.licence-type.licence-type-create />
    <hr>
    @endcan
    @can('read licence_types')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Sürücü Belgesi Seçenekleri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:tenant.licence-type.licence-type-table />
            </div>
        </div>
    </div>
    @endcan
</div>
