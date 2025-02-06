<div>
    @can('create licence_type_categories')
    <livewire:tenant.licence-type-category.licence-type-category-create />
    <hr>
    @endcan
    @can('read licence_type_categories')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Sürücü Belgesi Kategorileri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:tenant.licence-type-category.licence-type-category-table />
            </div>
        </div>
    </div>
    @endcan
</div>
