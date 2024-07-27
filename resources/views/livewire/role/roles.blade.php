<div>
    @can('create roles')
    <livewire:role.role-create />
    <hr>
    @endcan
    @can('read roles')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Roller</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:role.role-table />
            </div>
        </div>
    </div>
    @endcan
</div>
