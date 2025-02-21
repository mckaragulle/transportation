<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dealers.list')}}">Bayiler</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Oluştur</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Bayi Oluştur</h4>
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
                            @if (auth()->user()->can('update dealer_type_categories'))
                                <div class="mb-3 row">
                                    @if(is_iterable($dealerTypeCategoryDatas))
                                    @foreach ($dealerTypeCategoryDatas as $dealerTypeCategory)
                                        <div class="col-lg-2 col-sm-12">
                                            <label class="col-form-label">{{ $dealerTypeCategory->name }} SEÇİNİZ
                                                :</label>
                                            <select wire:model="dealer_type_categories.{{ $dealerTypeCategory->id }}"
                                                id="dealer_type_category_id{{ $dealerTypeCategory->id }}"
                                                class="form-select form-select-lg" {{$dealerTypeCategory->is_required?'required':''}} {{$dealerTypeCategory->is_multiple?'multiple':''}}>
                                                <option value="">{{ $dealerTypeCategory->name }} SEÇİNİZ</option>
                                                @if (is_iterable($dealerTypeCategory->dealer_types))
                                                    @forelse($dealerTypeCategory->dealer_types as $dealerType)
                                                        @if (count($dealerType->dealer_types) == 0)
                                                            <option value="{{ $dealerType->id }}">
                                                                {{ isset($dealerType->dealer_type->name) ? $dealerType->dealer_type->name . ' => ' : '' }}{{ $dealerType->name }}
                                                            </option>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                            @error('dealer_type_categories')
                                                <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="btn-close">
                                                    </button>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endforeach
                                    @endif
                                </div>
                            @endif

                            <div class="mb-3 row">
                                <div class="col-sm-3 col-form-label">Bayi adını yazınız:</div>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" required wire:model.defer="name"
                                    placeholder="Bayi adını yazınız.">
                                    @error('name')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-sm-3 col-form-label">Bayi kısa adını yazınız:</div>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" required wire:model.defer="shortname"
                                    placeholder="Bayi kısa adını yazınız.">
                                    @error('shortname')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Telefon :</label>
                                <div class="col-sm-9">
                                    <input class="form-control border border-warning" type="text"
                                           wire:model.defer="phone" placeholder="Telefon yazınız.">
                                    @error('phone')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">E-posta :</label>
                                <div class="col-sm-9">
                                    <input class="form-control border border-warning" type="email"
                                           wire:model.defer="email" placeholder="E-posta yazınız." autocomplete="false">
                                    @error('email')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">TC Kimlik / Vergi numarasını yazınız:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="number" wire:model.defer="tax"
                                        placeholder="TC Kimlik / Vergi numarasını yazınız:">
                                    @error('tax')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Vergi dairesini yazınız:</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" wire:model.defer="taxoffice"
                                    placeholder="Vergi dairesini yazınız:">
                                    @error('taxoffice')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="row mb-3">
                                <div class="col-sm-3 col-form-label">Şifre :</div>
                                <div class="col-sm-9 row">
                                    <div class="col-lg-12 col-sm-12 mb-3">
                                        <input class="form-control border border-warning" type="password"
                                               wire:model.defer="password" placeholder="Şifre yazınız.">
                                        @error('password')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="btn-close">
                                            </button>{{$message}}
                                        </div>@enderror
                                    </div>
                                    <div class="col-lg-12 col-sm-12 mb-3">
                                        <input class="form-control border border-warning" type="password"
                                               wire:model.defer="password_confirmation"
                                               placeholder="Şifreyi tekrar yazınız.">
                                        @error('password_confirmation')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="btn-close">
                                            </button>{{$message}}
                                        </div>@enderror
                                    </div>


                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">Açıklama yazınız:</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" type="text" wire:model.defer="detail"
                                placeholder="Açıklama yazınız."></textarea>
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

