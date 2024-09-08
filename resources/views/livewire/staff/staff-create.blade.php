<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('staffs.list') }}">Staffler</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Oluştur</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Staff Oluştur</h4>
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
                            @if (auth()->user()->can('update staff_type_categories'))
                                <div class="mb-3 row">
                                    @foreach ($staffTypeCategoryDatas as $staffTypeCategory)
                                        <div class="col-lg-2 col-sm-12">
                                            <label class="col-form-label">{{ $staffTypeCategory->name }} SEÇİNİZ
                                                :</label>
                                            <select wire:model.lazy="staff_type_categories.{{ $staffTypeCategory->id }}"
                                                id="staff_type_category_id{{ $staffTypeCategory->id }}"
                                                class="form-select form-select-lg">
                                                <option value="">{{ $staffTypeCategory->name }} SEÇİNİZ</option>
                                                @if (is_iterable($staffTypeCategory->staff_types))
                                                    @forelse($staffTypeCategory->staff_types as $staffType)
                                                        @if (count($staffType->staff_types) == 0)
                                                            <option value="{{ $staffType->id }}">
                                                                {{ isset($staffType->staff_type->name) ? $staffType->staff_type->name . ' => ' : '' }}{{ $staffType->name }}
                                                            </option>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                            @error('staff_type_categories')
                                                <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                        aria-label="btn-close">
                                                    </button>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endforeach

                                </div>
                            @endif
                            <hr />
                            <div class="mb-3 row">
                                <div class="col-sm-2">
                                    <label class="col-form-label">Personel TC kimlik numarasını yazınız:</label>
                                    <input class="form-control" type="number" wire:model.defer="id_number"
                                        placeholder="Personel TC kimlik numarasını yazınız.">
                                    @error('id_number')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Personel adını yazınız:</label>
                                    <input class="form-control" type="text" wire:model.defer="name"
                                        placeholder="Personel adını yazınız.">
                                    @error('name')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Personel soyadını yazınız:</label>
                                    <input class="form-control" type="text" wire:model.defer="surname"
                                        placeholder="Personel soyadını yazınız.">
                                    @error('surname')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Personel 1. telefonunu yazınız:</label>
                                    <input class="form-control" type="text" wire:model.defer="phone1"
                                        placeholder="Personel 1. telefonunu yazınız.">
                                    @error('phone1')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Personel 2. telefonunu yazınız:</label>
                                    <input class="form-control" type="text" wire:model.defer="phone2"
                                        placeholder="Personel 2. telefonunu yazınız.">
                                    @error('phone2')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Personel eposta adresini yazınız:</label>
                                    <input class="form-control" type="text" wire:model.defer="email"
                                        placeholder="Personel eposta adresini yazınız.">
                                    @error('email')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Açıklama yazınız:</label>
                                    <textarea class="form-control" type="text" wire:model.defer="detail" placeholder="Açıklama yazınız."></textarea>
                                    @error('detail')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <label class="col-form-label">Dosya seçiniz:</label>
                                    <input class="form-control" type="file" wire:model="filename" />
                                    <div wire:loading wire:target="photo">Uploading...</div>
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
