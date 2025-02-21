<div>
    @can('create account_types')
    <livewire:landlord.account-type.account-type-create />
    <hr>
    @endcan
    @can('read account_types')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Cariler</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.account-type.account-type-table />
            </div>
        </div>
    </div>
    @endcan
</div>
