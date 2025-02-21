<div class="col-xl-12">
    @if($is_show)
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('account_addresses.list') }}">Cari Bankaları</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Oluştur</a></li>
        </ol>
    </div>
    @endif
    <div class="card overflow-hidden">
        @if($is_show)
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Cari Banka Oluştur</h4>
        </div>
        @endif
        <div class="card-body {{$is_show ? '':'p-0'}}">
            <div class="basic-form">
                <form wire:submit.prevent="store">
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
                                    <select wire:model.lazy="bank_id" id="bank_id"
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
