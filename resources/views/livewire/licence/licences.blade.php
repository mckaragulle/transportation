<div>
    @can('create licences')
    <livewire:licence.licence-create />
    <hr>
    @endcan
    @can('read licences')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Sürücü Belgeleri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:licence.licence-table />
            </div>
        </div>
    </div>
    @endcan
</div>