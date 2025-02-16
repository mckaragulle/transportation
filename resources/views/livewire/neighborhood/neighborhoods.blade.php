<div>
    @can('create neighborhoods')
    <livewire:landlord.neighborhood.neighborhood-create />
    <hr>
    @endcan
    @can('read neighborhoods')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">İlçeler</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.neighborhood.neighborhood-table />
            </div>
        </div>
    </div>
    @endcan
</div>
