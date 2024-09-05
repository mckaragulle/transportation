<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('vehicle_models.list') }}">Modeller</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Düzenle</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Model Düzenle</h4>
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
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            @if (auth()->user()->can('update vehicle_brands'))
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Marka Seçiniz :</label>
                                    <div class="col-sm-3">
                                        <select wire:model.lazy="vehicle_brand_id" id="vehicle_brand_id"
                                            class="select2 form-select form-select-lg">
                                            <option value="">Marka Seçiniz</option>
                                            @if (is_iterable($vehicleBrands))
                                                @forelse($vehicleBrands as $d)
                                                    <option value="{{ $d->id }}"
                                                        {{ $d->id == $vehicle_brand_id ? 'selected' : '' }}>
                                                        {{ $d->name }}</option>
                                                @empty
                                                @endforelse
                                            @endif
                                        </select>
                                        @error('vehicle_brand_id')
                                            <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="btn-close">
                                                </button>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            @if (auth()->user()->can('update vehicle_tickets'))
                                <div class="mb-3 row">
                                    <label class="col-sm-3 col-form-label">Tip Seçiniz :</label>
                                    <div class="col-sm-3">
                                        <select wire:model.lazy="vehicle_ticket_id" id="vehicle_ticket_id"
                                            class="select2 form-select form-select-lg">
                                            <option value="">Marka Seçiniz</option>
                                            @if (is_iterable($vehicleTickets))
                                                @forelse($vehicleTickets as $d)
                                                    <option value="{{ $d->id }}"
                                                        {{ $d->id == $vehicle_ticket_id ? 'selected' : '' }}>
                                                        {{ $d->name }}</option>
                                                @empty
                                                @endforelse
                                            @endif
                                        </select>
                                        @error('vehicle_ticket_id')
                                            <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="btn-close">
                                                </button>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Araç modelini yazınız :</label>
                                <div class="col-sm-3">
                                    <input class="form-control border border-warning" type="text"
                                        wire:model.defer="name" placeholder="Araç modelini yazınız.">
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
                                <label class="col-sm-3 col-form-label">Kasko kodunu yazınız :</label>
                                <div class="col-sm-9">
                                    <input class="form-control border border-warning" type="text"
                                        wire:model.defer="insurance_number" placeholder="Kasko kodu yazınız.">
                                    @error('insurance_number')
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
