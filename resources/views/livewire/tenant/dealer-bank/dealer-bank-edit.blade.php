<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dealer_banks.list') }}">Bayi Banka Bilgileri</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Düzenle</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Bayi Banka Bilgisi Düzenle</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <form wire:submit.prevent="update">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="mb-3 row">
                                <div class="col-sm-12">
                                    <label class="col-form-label">Durum :</label>
                                    <div class="form-check fs-6 mt-2">
                                        <input class="form-check-input" wire:model.defer="status" id="status"
                                            type="checkbox">
                                        <label class="form-check-label" for="status">AKTİF</label>
                                    </div>
                                    @error('status')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <hr />

                            <div class="mb-3 row">
                                <div class="col-sm-3">
                                    <label class="col-form-label">Banka Seçiniz :</label>
                                    <select wire:model="bank_id" id="bank_id"
                                        class="form-select form-select-lg">
                                        <option value="">Banka Seçiniz</option>
                                        @if(is_iterable($banks))
                                        @forelse($banks as $b)
                                        <option value="{{$b->id}}">{{$b->name}}</option>
                                        @empty
                                        @endforelse
                                        @endif
                                    </select>
                                    @error('bank_id')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">İban adresini yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.lazy="iban"
                                        placeholder="İban adresini yazınız.">
                                    @error('iban')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
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
