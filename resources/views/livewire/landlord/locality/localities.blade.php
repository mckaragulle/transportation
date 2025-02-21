<div>
    @can('create localities')
    <livewire:locality.locality-create />
    <hr>
    @endcan
    @can('read localities')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Semtler</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:locality.locality-table />
            </div>
        </div>
    </div>
    @endcan
</div>