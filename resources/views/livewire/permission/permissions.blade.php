<div>
    @can('create permissions')
    <livewire:permission.permission-create />
    <hr>
    @endcan
    @can('read permissions')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">İzinler</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:permission.permission-table />
            </div>
        </div>
    </div>
    @endcan
</div>
