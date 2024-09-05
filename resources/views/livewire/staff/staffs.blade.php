<div>
    @can('create staffs')
    <livewire:staff.staff-create />
    <hr>
    @endcan
    @can('read staffs')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Personeller</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:staff.staff-table />
            </div>
        </div>
    </div>
    @endcan
</div>