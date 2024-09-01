<div class="col-xl-12">
    <div class="row page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('hgses.list') }}">Hgsler</a></li>
            <li class="breadcrumb-item active"><a href="javascript:void(0)">Düzenle</a></li>
        </ol>
    </div>
    <div class="card overflow-hidden">
        <div class="card-header border-bottom border-warning warning">
            <h4 class="card-title mb-0">Hgs Düzenle</h4>
        </div>
        <div class="card-body">
            <div class="basic-form">
                <form wire:submit.prevent="update">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <div class="mb-3 row">
                                <div class="col-sm-3">
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

                            @if (auth()->user()->can('update hgs_type_categories'))
                                <div class="mb-3 row">
                                    @foreach ($hgsTypeCategoryDatas as $hgsTypeCategory)
                                        <div class="col-lg-2 col-sm-12">
                                            <label class="col-form-label">{{ $hgsTypeCategory->name }} SEÇİNİZ :</label>
                                            <select wire:model.lazy="hgs_type_categories.{{ $hgsTypeCategory->id }}"
                                                id="hgs_type_category_id{{ $hgsTypeCategory->id }}"
                                                class="form-select form-select-lg">
                                                <option value="">{{ $hgsTypeCategory->name }} SEÇİNİZ</option>
                                                @if (is_iterable($hgsTypeCategory->hgs_types))
                                                    @forelse($hgsTypeCategory->hgs_types as $hgsType)
                                                        @if (count($hgsType->hgs_types) == 0)
                                                            <option value="{{ $hgsType->id }}" {{in_array($hgsType->id, $hgs_types)? 'selected':''}}>
                                                                {{ isset($hgsType->hgs_type->name) ? $hgsType->hgs_type->name . ' => ' : '' }}{{ $hgsType->name }}
                                                            </option>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                @endif
                                            </select>
                                            @error('hgs_type_categories')
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
                                <label class="col-sm-2 col-form-label">Hgs numarasını yazınız:</label>
                                <div class="col-sm-3">
                                    <input class="form-control" type="number" step="1" wire:model.defer="number"
                                        placeholder="Hgs numarasını yazınız.">
                                    @error('number')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Dosya seçiniz:</label>
                                <div class="col-sm-3">
                                    <input class="form-control" type="file" wire:model="filename" />
                                    <div wire:loading wire:target="photo">Uploading...</div>
                                    @error('filename')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                @if ($filename)
                                <div class="col-sm-2">
                                    <img src="{{ $filename->temporaryUrl() }}" width="100">
                                </div>
                                @endif
                                @if ($oldfilename)
                                <div class="col-sm-2">
                                    <img src="{{ \Storage::url($oldfilename) }}" width="100">
                                </div>
                                @endif
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Alınma Tarihini Seçiniz:</label>
                                <div class="col-sm-3">
                                    <div wire:ignore>
                                        <input wire:model="buyed_at" class="form-control" name="buyed_at"
                                            id="buyed_at" />
                                    </div>
                                    @error('buyed_at')
                                        <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="btn-close">
                                            </button>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">İptal Tarihini Seçiniz:</label>
                                <div class="col-sm-3">
                                    <div wire:ignore>
                                        <input wire:model="canceled_at" class="form-control" name="canceled_at"
                                            id="canceled_at" />
                                    </div>
                                    @error('canceled_at')
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
            var buyed_at = new Pikaday({
                field: document.getElementById('buyed_at'),
                format: 'YYYY-MM-DD',
                i18n: trDate,
                onSelect: function() {
                    var data = this.getDate();
                    @this.set('buyed_at', data);
                }
            });
            var canceled_at = new Pikaday({
                field: document.getElementById('canceled_at'),
                format: 'YYYY-MM-DD',
                i18n: trDate,
                onSelect: function() {
                    var data = this.getDate();
                    @this.set('canceled_at', data);
                }
            });
        </script>
    @endpush
</div>
