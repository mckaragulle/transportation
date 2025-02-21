<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dealer_managements.edit', ['id' => $dealer_id]) }}">Bayi Adresleri</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Düzenle</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Bayi Adresi Düzenle</h4>
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
                                    <label class="col-form-label">İl Seçiniz :</label>
                                    <select wire:model="city_id" id="city_id"
                                        class="form-select form-select-lg">
                                        <option value="">İl Seçiniz</option>
                                        @if(is_iterable($cities))
                                        @forelse($cities as $c)
                                        <option value="{{$c->id}}">{{$c->name}}</option>
                                        @empty
                                        @endforelse
                                        @endif
                                    </select>
                                    @error('city_id')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-form-label">İlçe Seçiniz :</label>
                                    <select wire:model="district_id" id="district_id"
                                        class="form-select form-select-lg">
                                        <option value="">İlçe Seçiniz</option>
                                        @if(is_iterable($districts))
                                        @forelse($districts as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @empty
                                        @endforelse
                                        @endif
                                    </select>
                                    @error('district_id')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-form-label">Mahalle Seçiniz :</label>
                                    <select wire:model="neighborhood_id" id="neighborhood_id"
                                        class="form-select form-select-lg">
                                        <option value="">Mahalle Seçiniz</option>
                                        @if(is_iterable($neighborhoods))
                                        @forelse($neighborhoods as $n)
                                        <option value="{{$n->id}}">{{$n->name}}</option>
                                        @empty
                                        @endforelse
                                        @endif
                                    </select>
                                    @error('neighborhood_id')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-form-label">Semt Seçiniz :</label>
                                    <select wire:model="locality_id" id="locality_id"
                                        class="form-select form-select-lg">
                                        <option value="">Semt Seçiniz</option>
                                        @if(is_iterable($localities))
                                        @forelse($localities as $l)
                                        <option value="{{$l->id}}">{{$l->name}}</option>
                                        @empty
                                        @endforelse
                                        @endif
                                    </select>
                                    @error('locality_id')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Bayi adres başlığını yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="name"
                                        placeholder="Bayi adres başlığını yazınız.">
                                    @error('name')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">1. Telefonu yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="phone1"
                                        placeholder="1. Telefonu yazınız.">
                                    @error('phone1')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">2. Telefonu yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="phone2"
                                        placeholder="2. Telefonu yazınız.">
                                    @error('phone2')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Eposta adresini yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="email"
                                        placeholder="Eposta adresini yazınız.">
                                    @error('email')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-sm-2">
                                    <label class="col-form-label">1. Adresi yazınız:</label>
                                    <textarea class="form-control" required wire:model.defer="address1" placeholder="1. Adresi yazınız."></textarea>
                                    @error('address1')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">2. Adresi yazınız:</label>
                                    <textarea class="form-control" required wire:model.defer="address2" placeholder="2. Adresi yazınız."></textarea>
                                    @error('address2')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Açıklama yazınız:</label>
                                    <textarea class="form-control" required wire:model.defer="detail" placeholder="Açıklama yazınız."></textarea>
                                    @error('detail')
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
