<div>
    @can('create branch_type_categories')
    <livewire:landlord.branch-type-category.branch-type-category-create />
    <hr>
    @endcan
    @can('read branch_type_categories')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Şube Kategorileri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.branch-type-category.branch-type-category-table />
            </div>
        </div>
    </div>
    @endcan
</div>
