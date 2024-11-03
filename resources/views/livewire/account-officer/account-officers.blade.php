<div>
    @can('create account_officers')
    <livewire:account-officer.account-officer-create />
    <hr>
    @endcan
    @can('read account_officers')
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">CARİ YETKİLİLERİ</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <livewire:account-officer.account-officer-table />
            </div>
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                    </button>
                    @foreach ($errors->all() as $key => $error)
                        {{ $error }}<br />
                        <div class="alert alert-danger alert-alt alert-dismissible fade show">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                class="me-2">
                                <polygon
                                    points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                </polygon>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                            <strong>HATA!</strong> {{ $error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="btn-close">
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endcan
</div>