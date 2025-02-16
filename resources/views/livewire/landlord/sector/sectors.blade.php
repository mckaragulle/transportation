<div>
    @can('create sectors')
    <livewire:landlord.sector.sector-create />
    <hr>
    @endcan
    @can('read sectors')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">CARİ SEKTÖRLERİ</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:landlord.sector.sector-table />
            </div>
        </div>
    </div>
    @endcan
</div>
