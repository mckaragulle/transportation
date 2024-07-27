<div>
    @can('create brands')
    <livewire:brand.brand-create />
    <hr>
    @endcan
    @can('read brands')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Markalar</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:brand.brand-table />
            </div>
        </div>
    </div>
    @endcan
</div>
