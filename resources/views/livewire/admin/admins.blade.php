<div>
    @can('create admins')
    <livewire:admin.admin-create />
    <hr>
    @endcan
    @can('read admins')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Yöneticiler</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:admin.admin-table />
            </div>
        </div>
    </div>
    @endcan
</div>
