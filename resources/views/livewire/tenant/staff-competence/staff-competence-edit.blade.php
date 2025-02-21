<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('staff_competences.list', ['id' => $staffCompetence->staff_id, 'is_show' => 1]) }}">Personel Yetkinlikleri</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Düzenle</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">{{$staffCompetence->staff_type_category->name}}</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Personel Yetkinliği Düzenle</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <form wire:submit.prevent="update">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="mb-3 row">

                            </div>
                            @if (auth()->user()->can('read staff_type_categories'))
                            
                            <div class="mb-3 row">
                                <div class="col-lg-1 col-sm-12">
                                    <label class="col-form-label">Durum :</label>
                                    <div class="form-check fs-6 mt-2">
                                        <input class="form-check-input" wire:model="status" id="status"
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

                                <div class="col-lg-2 col-sm-12">
                                    <label class="col-form-label">{{$staffCompetence->staff_type_category->name}} SEÇİNİZ:</label>
                                    <select wire:model="staff_type_id"
                                        id="staff_type_id"
                                        class="form-select form-select-lg">
                                        <option value="">{{$staffCompetence->staff_type_category->name}} SEÇİNİZ</option>
                                        @if (is_iterable($staffTypes))
                                            @forelse($staffTypes as $staffType)
                                                @if (count($staffType->staff_types) == 0)
                                                    <option value="{{ $staffType->id }}">
                                                        {{ isset($staffType->staff_type->name) ? $staffType->staff_type->name . ' => ' : '' }}{{ $staffType->name }}
                                                    </option>
                                                @endif
                                            @empty
                                            @endforelse
                                        @endif
                                    </select>
                                    @error('staff_type_id')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <label class="col-form-label">Geçerlilik Tarihini Seçiniz:</label>
                                    <div wire:ignore>
                                        <input wire:model.defer="expiry_date_at" class="form-control" name="expiry_date_at"
                                            id="expiry_date_at" />
                                    </div>
                                    
                                    @error('expiry_date_at')
                                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="btn-close">
                                        </button>{{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            
                            @endif
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
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
        <script>
        var Today = new Date();
        var trDate = {
            previousMonth: 'Önceki Ay',
            nextMonth: 'Sonraki Ay',
            months: ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim',
                'Kasım', 'Aralık'
            ],
            weekdays: ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi', 'Pazar'],
            weekdaysShort: ['Pts', 'Sl', 'Çrş', 'Prş', 'Cm', 'Cts', 'Pzr']
        };
        var expiry_date_at = new Pikaday({
            field: document.getElementById('expiry_date_at'),
            format: 'YYYY-MM-DD',
            i18n: trDate,
            onSelect: function() {
                var data = this.getDate();
                @this.set('expiry_date_at', data);
            }
        });
        </script>
    @endpush
</div>
