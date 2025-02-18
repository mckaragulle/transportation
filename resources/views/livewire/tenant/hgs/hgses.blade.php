<div>
    @can('create hgses')
    <livewire:tenant.hgs.hgs-create />
    <hr>
    @endcan
    @can('read hgses')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">HGSLER</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:tenant.hgs.hgs-table />
            </div>
        </div>
    </div>
    @endcan
</div>
