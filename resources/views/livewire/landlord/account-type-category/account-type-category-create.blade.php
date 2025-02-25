<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('account_type_categories.list')}}">Cari Kategorileri</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Oluştur</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Cari Kategorisi Oluştur</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <form wire:submit.prevent="store">
                    <div class="row">
                        <div class="col-lg-4 col-sm-12">
                            <div class="mb-3 row">
                                <div class="col-sm-4 col-form-label">Durum :</div>
                                <div class="col-sm-8">
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
                                <div class="col-sm-4 col-form-label">Zorunlu Seçenek Mi? :</div>
                                <div class="col-sm-8">
                                    <div class="form-check fs-6 mt-2">
                                        <input class="form-check-input" wire:model.defer="is_required" id="is_required"
                                            type="checkbox">
                                        <label class="form-check-label" for="is_required">EVET</label>
                                    </div>
                                    @error('is_required')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-4 col-form-label">Çoklu Seçim mi? :</div>
                                <div class="col-sm-8">
                                    <div class="form-check fs-6 mt-2">
                                        <input class="form-check-input" wire:model.defer="is_multiple" id="is_multiple"
                                            type="checkbox">
                                        <label class="form-check-label" for="is_multiple">EVET</label>
                                    </div>
                                    @error('is_multiple')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Cari kategorisini yazınız :</label>
                                <div class="col-sm-8">
                                    <input class="form-control border border-warning" type="text"
                                        wire:model.defer="name" placeholder="Cari kategorisini yazınız.">
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