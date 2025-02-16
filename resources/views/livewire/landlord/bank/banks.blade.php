<div>
    @can('create banks')
    <livewire:landlord.bank.bank-create />
    <hr>
    @endcan
    @can('read banks')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Bankalar</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.bank.bank-table />
            </div>
        </div>
    </div>
    @endcan
</div>