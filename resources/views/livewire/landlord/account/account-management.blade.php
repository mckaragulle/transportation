<div class="col-xl-12">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Cari Yönetimi</h4>
        </div>
        <div class="card-body">
            <!-- Nav tabs -->
            <div class="custom-tab-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#home1"><i class="la la-home me-2"></i> Cari Bilgileri</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="home1" role="tabpanel">
                        <div class="pt-4">
                            <livewire:account.account-edit id="{{$account->id}}" is_show="0" />
                            <div class="custom-tab-2">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#addresses"><i class="la la-envelope me-2"></i> Adreslerim</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#banks"><i class="la la-money me-2"></i>  Banka Bilgilerim</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#officers"><i class="la la-user me-2"></i> Yetkililer</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#files"><i class="la la-file me-2"></i> Dosyalar</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#qrcode"><i class="la la-qrcode me-2"></i> Qr Code</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="addresses">
                                        <div class="pt-4">
                                            <livewire:landlord.account-address.account-addresses id="{{$account->id}}" is_show="0" />
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="banks">
                                        <div class="pt-4">
                                            <livewire:landlord.account-bank.account-banks id="{{$account->id}}" is_show="0" />
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="officers">
                                        <div class="pt-4">
                                            <livewire:landlord.account-officer.account-officers id="{{$account->id}}" is_show="0" />
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="files">
                                        <div class="pt-4">
                                            <livewire:landlord.account-file.account-files id="{{$account->id}}" is_show="0" />
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="qrcode">
                                        <div class="pt-4">
                                            {{QrCode::size(250)->generate($account->id)}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
