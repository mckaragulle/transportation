<div>
    @can('create districts')
    <livewire:landlord.district.district-create />
    <hr>
    @endcan
    @can('read districts')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">İlçeler</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.district.district-table />
            </div>
        </div>
    </div>
    @endcan
</div>