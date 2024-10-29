<div>
    @can('create cities')
    <livewire:city.city-create />
    <hr>
    @endcan
    @can('read cities')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">İller</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:city.city-table />
            </div>
        </div>
    </div>
    @endcan
</div>
