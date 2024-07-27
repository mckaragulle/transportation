<div>
    @can('create dealers')
    <livewire:dealer.dealer-create />
    <hr>
    @endcan
    @can('read dealers')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Bayiler</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:dealer.dealer-table />
            </div>
        </div>
    </div>
    @endcan
</div>
