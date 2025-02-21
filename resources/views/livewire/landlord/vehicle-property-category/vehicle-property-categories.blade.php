<div>
    @can('create vehicle_property_categories')
    <livewire:landlord.vehicle-property-category.vehicle-property-category-create />
    <hr>
    @endcan
    @can('read vehicle_property_categories')
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Araç Özellik Kategorileri</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.vehicle-property-category.vehicle-property-category-table />
            </div>
        </div>
    </div>
    @endcan
</div>
