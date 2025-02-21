<div>
    @can('create fineds')
    <livewire:tenant.fined.fined-create />
    <hr>
    @endcan
    @can('read fineds')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Araç Cezaları</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:tenant.fined.fined-table />
            </div>
        </div>
    </div>
    @endcan
</div>
