<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('permissions.list')}}">İzinler</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Düzenle</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden border border-warning">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">İzin Düzenle</h4>
        </div>
        <div class="card-body">

            <div class="basic-form">
                <form wire:submit.prevent="update">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">İzin Adı :</label>
                                <div class="col-sm-9">
                                    <input class="form-control border border-warning" type="text"
                                           wire:model.defer="name" placeholder="İzin adını yazınız.">
                                    @error('name')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                        </button>{{$message}}
                                    </div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12">
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
        $('#permission').select2({
            tags: true
        });
        $('#permission').on('change', function () {
        @this.set('permission', $(this).val());
        });
    </script>
@endpush
