<div>
    @can('create dealer_type_categories')
    <livewire:dealer-type-category.dealer-type-category-create />
    <hr>
    @endcan
    @can('read dealer_type_categories')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Bayi Kategorileri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:dealer-type-category.dealer-type-category-table />
            </div>
        </div>
    </div>
    @endcan
</div>