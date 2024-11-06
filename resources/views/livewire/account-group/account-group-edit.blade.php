<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('account_officers.list') }}">Cari Yetkilileri</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Düzenle</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Cari Yetkilisi Düzenle</h4>
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
                                    <label class="col-form-label">Hesap Seçiniz :</label>
                                    <select wire:model.lazy="account_id" id="account_id"
                                        class="form-select form-select-lg">
                                        <option value="">Hesap Seçiniz</option>
                                        @if(is_iterable($accounts))
                                        @forelse($accounts as $a)
                                        <option value="{{$a->id}}">{{$a->name}}</option>
                                        @empty
                                        @endforelse
                                        @endif
                                    </select>
                                    @error('account_id')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Yetkili no'sunu yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="number"
                                        placeholder="Yetkili no'sunu yazınız.">
                                    @error('number')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Yetkili adını yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="name"
                                        placeholder="Yetkili adını yazınız.">
                                    @error('name')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Yetkili soyadını yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="surname"
                                        placeholder="Yetkili soyadını yazınız.">
                                    @error('surname')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-sm-2">
                                    <label class="col-form-label">Yetkili ünvanını yazınız:</label>
                                    <input class="form-control" type="text" required wire:model.defer="title"
                                        placeholder="Yetkili ünvanını yazınız.">
                                    @error('title')
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
                                <div class="col-sm-3">
                                    <label class="col-form-label">Dosya/ları seçiniz:</label>
                                    <input class="form-control" type="file" wire:model.lazy="files" multiple />
                                    <div wire:loading wire:target="files">Yükleniyor...</div>
                                    @error('files')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                @if (is_array($oldfiles) && count($oldfiles) > 0)
                                <div class="row">
                                @foreach($oldfiles as $k => $oldfile)
                                @if(\Storage::exists($oldfile))
                                <div class="col-sm-1">
                                    <a href="{{\Storage::url($oldfile)}}" target="_blank">{{$k+1}}. Dosya</a>
                                </div>
                                @endif
                                @endforeach

                                </div>
                                @endif
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