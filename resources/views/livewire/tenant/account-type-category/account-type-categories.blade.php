<div>
    @can('create account_type_categories')
    <livewire:tenant.account-type-category.account-type-category-create />
    <hr>
    @endcan
    @can('read account_type_categories')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Cari Kategorileri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:tenant.account-type-category.account-type-category-table />
            </div>
        </div>
    </div>
    @endcan
</div>
