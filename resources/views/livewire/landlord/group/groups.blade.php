<div>
    @can('create groups')
    <livewire:landlord.group.group-create />
    <hr>
    @endcan
    @can('read groups')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">CARİ GRUPLARI</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.group.group-table />
            </div>
        </div>
    </div>
    @endcan
</div>
