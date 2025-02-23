<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('staff_type_categories.list')}}">Personel Kategorileri</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Oluştur</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Personel Kategorisi Oluştur</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <form wire:submit.prevent="store">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12">
                            <div class="mb-3 row">
                                <div class="col-sm-3 col-form-label">Durum :</div>
                                <div class="col-sm-9">
                                    <div class="form-check fs-6 mt-2">
                                        <input class="form-check-input" wire:model.defer="status" id="status"
                                            type="checkbox">
                                        <label class="form-check-label" for="status">AKTİF</label>
                                    </div>
                                    @error('status')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Hangi Modülde Kullanılsın? :</label>
                                <div class="col-sm-9">
                                    <select wire:model="target" id="target" class="form-select form-select-lg">
                                        <option value="">Hangi Modülde Kullanılsın?</option>
                                        <option value="all">HEPSİ</option>
                                        <option value="staff">PERSONEL</option>
                                        <option value="competence">YETKİNLİKLERİM</option>
                                       {{-- <option value="address">ADRES</option>
                                        <option value="bank">BANKA</option>
                                        <option value="file">DOSYA</option>--}}
                                    </select>
                                    @error('target')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Personel kategorisini yazınız :</label>
                                <div class="col-sm-9">
                                    <input class="form-control border border-warning" type="text"
                                        wire:model.defer="name" placeholder="Personel kategorisini yazınız.">
                                    @error('name')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-rounded btn-success"><span
                                        class="btn-icon-start text-success"><i
                                            class="fa fa-floppy-disk color-info"></i></span> KAYDET
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
