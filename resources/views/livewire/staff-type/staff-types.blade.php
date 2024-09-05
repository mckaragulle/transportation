<div>
    @can('create staff_types')
    <livewire:staff-type.staff-type-create />
    <hr>
    @endcan
    @can('read staff_types')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Personeller</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:staff-type.staff-type-table />
            </div>
        </div>
    </div>
    @endcan
</div>