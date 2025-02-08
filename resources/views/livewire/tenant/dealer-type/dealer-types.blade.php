<div>
    @can('create dealer_types')
    <livewire:dealer-type.dealer-type-create />
    <hr>
    @endcan
    @can('read dealer_types')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Bayi Seçenekleri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:dealer-type.dealer-type-table />
            </div>
        </div>
    </div>
    @endcan
</div>