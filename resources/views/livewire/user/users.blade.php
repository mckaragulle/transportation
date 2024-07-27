<div>
    @can('create users')
    <livewire:user.user-create />
    <hr>
    @endcan
    @can('read users')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Personeller</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:user.user-table />
            </div>
        </div>
    </div>
    @endcan
</div>
