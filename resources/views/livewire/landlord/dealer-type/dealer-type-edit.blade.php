<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dealer_types.list')}}">Bayi Seçenekleri</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Düzenle</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Bayi Seçeneği Düzenle</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <form wire:submit.prevent="update">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
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

                            @if(auth()->user()->can('update dealer_type_categories'))
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Bayi Kategorisini Seçiniz :</label>
                                <div class="col-sm-3">
                                    <select wire:model="dealer_type_category_id" id="dealer_type_category_id"
                                        class="form-select form-select-lg">
                                        <option value="">Bayi Kategorisi</option>
                                        @if(is_iterable($dealerTypeCategories))
                                        @forelse($dealerTypeCategories as $d)
                                        <option value="{{$d->id}}">{{$d->name}}</option>
                                        @empty
                                        @endforelse
                                        @endif
                                    </select>
                                    @error('dealer_type_category_id')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            @endif
                            @if(auth()->user()->can('update dealer_types'))
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Özellik Grubu Seçiniz :</label>
                                <div class="col-sm-3">
                                    <select wire:model="dealer_type_id" id="dealer_type_id"
                                        class="form-select form-select-lg">
                                        <option value="">Özellik Grubu Seçiniz</option>
                                        @if(is_iterable($dealerTypes))
                                        @forelse($dealerTypes as $d)
                                        @if($d->id != $dealerType->id && $d->id != $dealerType->dealer_type?->id)
                                        <option value="{{$d->id}}">{{($d->dealer_type?->name ? $d->dealer_type?->name . " -> " : '') . $d->name}}</option>
                                        @endif
                                        @empty
                                        @endforelse
                                        @endif
                                    </select>
                                    @error('dealer_type_id')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            @endif
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Bayi seçeneğini yazınız:</label>
                                <div class="col-sm-3">
                                    <input class="form-control" type="text" wire:model.defer="name"
                                        placeholder="Bayi seçeneğini yazınız.">
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
