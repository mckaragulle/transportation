<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('tenant.accounts.list') }}">Cariler</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Oluştur</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Cari Oluştur</h4>
        </div>
        <div class="card-body">
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
                            @if (auth()->user()->can('update account_type_categories'))
                                <div class="mb-3 row">
                                    @if(is_iterable($accountTypeCategoryDatas))
                                    @foreach ($accountTypeCategoryDatas as $accountTypeCategory)
                                        <div class="col-lg-2 col-sm-12">
                                            <label class="col-form-label">{{ $accountTypeCategory->name }} SEÇİNİZ
                                                :</label>
                                            <select wire:model="account_type_categories.{{ $accountTypeCategory->id }}"
                                                id="account_type_category_id{{ $accountTypeCategory->id }}"
                                                class="form-select form-select-lg" {{$accountTypeCategory->is_required?'required':''}} {{$accountTypeCategory->is_multiple?'multiple':''}}>
                                                <option value="">{{ $accountTypeCategory->name }} SEÇİNİZ</option>
                                                @if (is_iterable($accountTypeCategory->account_types))
                                                    @forelse($accountTypeCategory->account_types as $accountType)
                                                        @if (count($accountType->account_types) == 0)
                                                            <option value="{{ $accountType->id }}">
                                                                {{ isset($accountType->account_type->name) ? $accountType->account_type->name . ' => ' : '' }}{{ $accountType->name }}
                                                            </option>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                            @error('account_type_categories')
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
                            <hr />
                            <div class="mb-3 row">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Müşterinin cari numarasını yazınız:</label>
                                    <input class="form-control" type="number" required step="1" wire:model.defer="number"
                                        placeholder="Müşterinin cari numarasını yazınız.">
                                    @error('number')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Müşteri adını yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="name"
                                        placeholder="Müşteri adını yazınız.">
                                    @error('name')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Müşteri kısa adını yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="shortname"
                                        placeholder="Müşteri kısa adını yazınız.">
                                    @error('shortname')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Müşteri telefonunu yazınız:</label>
                                    <input class="form-control" type="text" wire:model.defer="phone"
                                        placeholder="Müşteri telefonunu yazınız.">
                                    @error('phone')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Müşteri eposta adresini yazınız:</label>
                                    <input class="form-control" type="text" wire:model.defer="email"
                                        placeholder="Müşteri eposta adresini yazınız.">
                                    @error('email')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">TC Kimlik / Vergi numarasını yazınız:</label>
                                    <input class="form-control" type="text" wire:model.defer="tax"
                                        placeholder="TC Kimlik / Vergi numarasını yazınız:">
                                    @error('tax')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Vergi dairesini yazınız:</label>
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
                                <div class="col-sm-2">
                                    <label class="col-form-label">Açıklama yazınız:</label>
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
                                {{-- <div class="col-sm-3">
                                    <label class="col-form-label">Dosya seçiniz:</label>
                                    <input class="form-control" type="file" wire:model="filename" />
                                    <div wire:loading wire:target="photo">Yükleniyor...</div>
                                    @error('filename')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                    @if ($filename)
                                        <img src="{{ $filename->temporaryUrl() }}" width="100">
                                    @endif
                                </div> --}}
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
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                            </button>
                            @foreach ($errors->all() as $key => $error)
                                {{ $error }}<br />
                            @endforeach
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        {{-- @include('components.errors') --}}
    </div>
</div>
