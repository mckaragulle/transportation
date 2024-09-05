<div>
    @can('create accounts')
    <livewire:account.account-create />
    <hr>
    @endcan
    @can('read accounts')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">CARİLER</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:account.account-table />
            </div>
        </div>
    </div>
    @endcan
</div>