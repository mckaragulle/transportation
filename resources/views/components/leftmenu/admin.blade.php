<li>
    <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
        <i class="fas fa-cogs "></i>
        <span class="nav-text">AYARLAR</span>
    </a>
    <ul aria-expanded="false" class="mm-collapse left" style="">
        <li>
            <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                <i class="fas fa-car "></i>
                <span class="ms-2">ARAÇLAR</span>
            </a>
            <ul aria-expanded="false" class="mm-collapse left" style="">
                <li>
                    <a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                        <i class="fas fa-car "></i>
                        <span class="ms-2">MARKALAR VE MODELLER</span>
                    </a>
                    <ul aria-expanded="false" class="mm-collapse left" style="">
                        @can('read vehicle_brands')
                            <li>
                                <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                    href="{{ route('vehicle_brands.list') }}"><i
                                        class="fa fa-car text-danger"></i> <span
                                        class="ms-2">Markalar</span></a>
                            </li>
                        @endcan
                        @can('read vehicle_tickets')
                            <li>
                                <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                    href="{{ route('vehicle_tickets.list') }}"><i
                                        class="fa fa-ticket text-danger"></i> <span
                                        class="ms-2">Tipler</span></a>
                            </li>
                        @endcan
                        @can('read vehicle_models')
                            <li>
                                <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                    href="{{ route('vehicle_models.list') }}"><i
                                        class="fas fa-calendar text-danger"></i> <span
                                        class="ms-2">Modeller</span></a>
                            </li>
                        @endcan

                    </ul>
                </li>
                <li>
                    <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                        <i class="fa-solid fa-list-check "></i>
                        <span class="m2">ARAÇ ÖZELLİKLERİ</span>
                    </a>
                    <ul aria-expanded="false" class="mm-collapse left" style="">
                        @can('read vehicle_property_categories')
                            <li>
                                <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                    href="{{ route('vehicle_property_categories.list') }}"><i
                                        class="fas fa-cogs text-danger"></i>
                                    <span class="ms-2">Özellik Kategorileri</span></a>
                            </li>
                        @endcan
                        @can('read vehicle_properties')
                            <li>
                                <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                    href="{{ route('vehicle_properties.list') }}"><i
                                        class="fas fa-cog text-danger"></i>
                                    <span class="ms-2">Araç Özellikleri</span></a>
                            </li>
                        @endcan
                    </ul>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                <i class="fa-solid fa-file-invoice"></i>
                <span class="ms-2">CARİLER</span>
            </a>
            <ul aria-expanded="false" class="mm-collapse left" style="">
                @can('read account_type_categories')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('account_type_categories.list') }}"><i
                                class="fas fa-users fas-sm text-danger"></i>
                            <span class="ms-2">Cari Kategorileri</span></a>
                    </li>
                @endcan
                @can('read account_types')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('account_types.list') }}"><i
                                class="fas fa-user text-danger"></i>
                            <span class="ms-2">Cari Seçenekleri</span></a>
                    </li>
                @endcan
                @can('read accounts')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('accounts.list') }}"><i
                                class="fas fa-users text-danger"></i>
                            <span class="ms-2">Cariler</span></a>
                    </li>
                @endcan
                {{-- @can('read account_addresses')
                        <li>
                            <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                href="{{ route('account_addresses.list') }}"><i
                                    class="fas fa-users text-danger"></i>
                                <span class="ms-2">Cari Adresleri</span></a>
                        </li>
                    @endcan
                    @can('read account_banks')
                        <li>
                            <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                href="{{ route('account_banks.list') }}"><i
                                    class="fa-solid fa-building-columns text-danger"></i>
                                <span class="ms-2">Cari Banka Bilgileri</span></a>
                        </li>
                    @endcan
                    @can('read account_officers')
                        <li>
                            <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                href="{{ route('account_officers.list') }}"><i
                                    class="fa-solid fa-user-tie text-danger"></i>
                                <span class="ms-2">Cari Yetkilileri</span></a>
                        </li>
                    @endcan
                    @can('read account_files')
                        <li>
                            <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                href="{{ route('account_files.list') }}"><i
                                    class="fa-solid fa-file text-danger"></i>
                                <span class="ms-2">Cari Dosyaları</span></a>
                        </li>
                    @endcan --}}
            </ul>
        </li>
        <li>
            <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                <i class="fa-solid fa-id-card"></i>
                <span class="ms-2">SÜRÜCÜ BELGELERİ</span>
            </a>
            <ul aria-expanded="false" class="mm-collapse left" style="">
                @can('read licence_type_categories')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('licence_type_categories.list') }}"><i
                                class="fa-solid fa-id-card text-danger"></i>
                            <span class="ms-2">Kategoriler</span></a>
                    </li>
                @endcan
                @can('read licence_types')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('licence_types.list') }}"><i
                                class="fa-solid fa-id-card text-danger"></i>
                            <span class="ms-2">Seçenekler</span></a>
                    </li>
                @endcan
                @can('read licences')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('licences.list') }}"><i
                                class="fa-solid fa-id-card text-danger"></i>
                            <span class="ms-2">Sürücü Belgeleri</span></a>
                    </li>
                @endcan
            </ul>
        </li>
        <li>
            <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                <i class="fas fa-ticket "></i>
                <span class="ms-2">HGS</span>
            </a>
            <ul aria-expanded="false" class="mm-collapse left" style="">
                @can('read hgs_type_categories')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('hgs_type_categories.list') }}"><i
                                class="fa fa-car text-danger"></i> <span class="ms-2">HGS
                                Kategorileri</span></a>
                    </li>
                @endcan
                @can('read hgs_types')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('hgs_types.list') }}"><i
                                class="fa fa-ticket text-danger"></i>
                            <span class="ms-2">HGS Tipleri</span></a>
                    </li>
                @endcan
                @can('read hgses')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('hgses.list') }}"><i
                                class="fas fa-ticket text-danger"></i>
                            <span class="ms-2">HGS'ler</span></a>
                    </li>
                @endcan

            </ul>
        </li>
        <li>
            <a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                <i class="fa-solid fa-users-gear "></i>
                <span class="ms-2">Personel</span>
            </a>
            <ul aria-expanded="false" class="mm-collapse left" style="">
                @can('read staff_type_categories')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('staff_type_categories.list') }}"><i
                                class="fa-solid fa-users-gear text-danger"></i> <span
                                class="ms-2">Kategoriler</span></a>
                    </li>
                @endcan
                @can('read staff_types')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('staff_types.list') }}"><i
                                class="fa-solid fa-users-gear text-danger"></i>
                            <span class="ms-2">Seçenekler</span></a>
                    </li>
                @endcan
                @can('read staffs')
                    <li>
                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                            href="{{ route('staffs.list') }}"><i
                                class="fa-solid fa-users-gear text-danger"></i>
                            <span class="ms-2">Personeller</span></a>
                    </li>
                @endcan
            </ul>
        </li>
    </ul>
</li>
@can('read fineds')
    <li>
        <a href="{{ route('fineds.list') }}" aria-expanded="false">
            <i class="fas fa-ticket"></i>
            <span class="nav-text">ARAÇ CEZALARI</span>
        </a>
    </li>
@endcan