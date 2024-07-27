<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('users.list')}}">Personeller</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Düzenle</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Personel Düzenle</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <form wire:submit.prevent="update">
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
                            @if($is_admin && auth()->user()->can('update users'))
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Bayi Seçiniz :</label>
                                <div class="col-sm-9">
                                    <select wire:model.lazy="dealer_id" id="dealer_id" class="select2 form-select form-select-lg">
                                        <option>Bayi Seçiniz</option>
                                        @if(is_iterable($dealers))
                                            @forelse($dealers as $d)
                                                <option value="{{$d->id}}" {{$d->id == $dealer_id ? 'selected' : ''}}>{{$d->name}}</option>
                                            @empty
                                            @endforelse
                                        @endif
                                    </select>
                                    @error('dealer_id')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                            @endif
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Departman Seçiniz :</label>
                                <div class="col-sm-9">
                                    <select id="role" class="select2 form-select form-select-lg" wire:model.defer="role">
                                        <option >Departman Seçiniz</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{$role->name == $role ? 'selected' : ''}}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-12">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">İsim :</label>
                                <div class="col-sm-9">
                                    <input class="form-control border border-warning" type="text"
                                           wire:model.defer="name" placeholder="Adını yazınız.">
                                    @error('name')
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
                                           wire:model.defer="email" placeholder="E-posta yazınız.">
                                    @error('email')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
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

@push('scripts')
    <script>
        $('#dealer_id').on('change', function () {
        @this.set('dealer_id', $(this).val());
        });
    </script>
@endpush
