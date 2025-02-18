<div>
    <form wire:submit.prevent="store">
        <div class="row">
            <div class="col-lg-3 col-sm-3">
                <label class="col-form-label">Adres Seçiniz :</label>
                <select wire:model.lazy="dealer_address_id" id="dealer_address_id"
                    class="form-select form-select-lg">
                    <option value="">Adres Seçiniz</option>
                    @if(is_iterable($addresses))
                    @forelse($addresses as $a)
                    <option value="{{$a->id}}">{{$a->name}}</option>
                    @empty
                    @endforelse
                    @endif
                </select>
                @error('dealer_address_id')
                <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="btn-close">
                    </button>{{$message}}
                </div>@enderror
            </div>

            <div class="col-lg-3 col-sm-3">
                <label class="col-form-label">Yetkili Seçiniz :</label>
                <select wire:model.lazy="dealer_officer_id" id="dealer_officer_id"
                    class="form-select form-select-lg">
                    <option value="">Yetkili Seçiniz</option>
                    @if(is_iterable($officers))
                    @forelse($officers as $o)
                    <option value="{{$o->id}}">{{"{$o->title}: {$o->name} {$o->surname}"}}</option>
                    @empty
                    @endforelse
                    @endif
                </select>
                @error('dealer_officer_id')
                <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="btn-close">
                    </button>{{$message}}
                </div>@enderror
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