<div>
    @can('create vehicle_property_categories')
    <livewire:staff-type-category.staff-type-category-create />
    <hr>
    @endcan
    @can('read vehicle_property_categories')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Personel Kategorileri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:staff-type-category.staff-type-category-table />
            </div>
        </div>
    </div>
    @endcan
</div>