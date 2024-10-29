<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Jobick : Job Admin Bootstrap 5 Template" />
    <meta name="format-detection" content="telephone=no">
    <title>TAŞIMACILIK PROGRAMI</title>
    {{-- <link rel="shortcut icon" type="image/png" href="{{ asset('xhtml/images/favicon.png') }}" /> --}}
    @vite('resources/css/app.css')
    @stack('styles')
    @livewireStyles
</head>

<body>

    <!--*******************
    Preloader start
********************-->
    <div id="preloader" class="notprintable">
        <div class="lds-ripple">
            <div></div>
            <div></div>
        </div>
    </div>
    <!--*******************
    Preloader end
********************-->

    <!--**********************************
    Main wrapper start
***********************************-->
    <div id="main-wrapper" class="show">

        <!--**********************************
        Nav header start
    ***********************************-->
        <div class="nav-header notprintable">
            <a href="{{ route('dashboard') }}" class="brand-logo">
                <img src="{{ asset('xhtml/images/logo-renkli.svg') }}" alt="">
            </a>
            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
        Nav header end
    ***********************************-->


        <!--**********************************
        Header start
    ***********************************-->
        <div class="header notprintable">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div>
                            <div class="header-left">
                                <div class="nav-item d-flex align-items-center">
                                    <div class="input-group search-area">
                                        <input type="text" class="form-control" wire:model.lazy="search">
                                        <span class="input-group-text"><a href="javascript:void(0)"><i
                                                    class="flaticon-381-search-2"></i></a></span>
                                    </div>
                                    <div class="plus-icon">
                                        <a href="1"><i class="fas fa-plus"></i></a>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                                    <img src="{{ asset('xhtml/images/profil-foto.jpg') }}" width="20"
                                        alt="" />
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    @can('read admins')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('admins.list') }}"><i class="fas fa-user-tie text-danger"></i>
                                            <span class="ms-2">Yöneticiler</span></a>
                                    @endcan
                                    @can('read roles')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('roles.list') }}"><i class="fas fa-user-secret text-danger"></i>
                                            <span class="ms-2">Roller</span></a>
                                    @endcan
                                    @can('read permissions')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('permissions.list') }}"><i
                                                class="fas fa-fingerprint text-danger"></i> <span
                                                class="ms-2">İzinler</span></a>
                                    @endcan
                                    <hr class="my-1" />
                                    @can('read cities')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('cities.list') }}"><i
                                                class="fas fa-user-group text-danger"></i> <span
                                                class="ms-2">İller</span></a>
                                    @endcan
                                    @can('read districts')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('districts.list') }}"><i
                                                class="fas fa-user-group text-danger"></i> <span
                                                class="ms-2">İlçeler</span></a>
                                    @endcan
                                    @can('read neighborhoods')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('neighborhoods.list') }}"><i
                                                class="fas fa-user-group text-danger"></i> <span
                                                class="ms-2">Mahalleler</span></a>
                                    @endcan
                                    @can('read localities')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('localities.list') }}"><i
                                                class="fas fa-user-group text-danger"></i> <span
                                                class="ms-2">Semtler</span></a>
                                    @endcan
                                    <hr class="my-1" />
                                    @can('read vehicle_brands')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('vehicle_brands.list') }}"><i class="fas fa-car text-danger"></i>
                                            <span class="ms-2">Markalar</span></a>
                                    @endcan
                                    @can('read vehicle_tickets')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('vehicle_tickets.list') }}"><i
                                                class="fas fa-car text-danger"></i>
                                            <span class="ms-2">Tipler</span></a>
                                    @endcan
                                    @can('read vehicle_models')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('vehicle_models.list') }}"><i class="fas fa-car text-danger"></i>
                                            <span class="ms-2">Modeller</span></a>
                                    @endcan
                                    @can('read vehicle_property_categories')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('vehicle_property_categories.list') }}"><i
                                                class="fas fa-car text-danger"></i>
                                            <span class="ms-2">Özellik Kategorileri</span></a>
                                    @endcan
                                    @can('read vehicle_properties')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('vehicle_properties.list') }}"><i
                                                class="fas fa-car text-danger"></i>
                                            <span class="ms-2">Özellikler</span></a>
                                    @endcan
                                    @can('read account_type_categories')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('account_type_categories.list') }}"><i
                                                class="fas fa-car text-danger"></i>
                                            <span class="ms-2">Cari Kategorileri</span></a>
                                    @endcan
                                    @can('read account_types')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('account_types.list') }}"><i class="fas fa-car text-danger"></i>
                                            <span class="ms-2">Cari Seçenekleri</span></a>
                                    @endcan

                                    @can('read hgs_type_categories')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('hgs_type_categories.list') }}"><i
                                                class="fas fa-car text-danger"></i>
                                            <span class="ms-2">HGS Kategorileri</span></a>
                                    @endcan
                                    @can('read hgs_types')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('hgs_types.list') }}"><i class="fas fa-car text-danger"></i>
                                            <span class="ms-2">HGS Seçenekleri</span></a>
                                    @endcan
                                    <hr class="my-1" />
                                    @can('read dealers')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('dealers.list') }}"><i class="fas fa-shop text-danger"></i>
                                            <span class="ms-2">Bayiler</span></a>
                                    @endcan

                                    @can('read users')
                                        <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                            href="{{ route('users.list') }}"><i
                                                class="fas fa-user-group text-danger"></i> <span
                                                class="ms-2">Personeller</span></a>
                                    @endcan

                                    <hr class="my-1" />

                                    <hr class="my-1" />
                                    <livewire:logout />
                                </div>
                            </li>
                            <li class="nav-item">

                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>

        <!--**********************************
        Header end ti-comment-alt
    ***********************************-->

        <!--**********************************
        Sidebar start
    ***********************************-->
        <div class="dlabnav notprintable">
            <div class="dlabnav-scroll">
                <div class="dropdown header-profile2 ">
                    <a class="nav-link " href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                        <div class="header-info2 d-flex align-items-center">
                            <img src="{{ asset('xhtml/images/profil-foto.jpg') }}" alt="">
                            <div class="d-flex align-items-center sidebar-info">
                                <div>
                                    <span class="font-w400 d-block">{{ auth()->user()->name ?? '' }}</span>
                                    <small
                                        class="text-end font-w400">{{ \Illuminate\Support\Str::ucfirst(auth()->user()->roles->first()->name ?? '') }}</small>
                                </div>
                                <i class="fas fa-chevron-down"></i>
                            </div>

                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="app-profile.html" class="dropdown-item ai-icon ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18"
                                height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="ms-2">Profile </span>
                        </a>
                        <livewire:logout />
                    </div>
                </div>
                <ul class="metismenu" id="menu">
                    <li>
                        <a href="{{ route('dashboard') }}" aria-expanded="false">
                            <i class="flaticon-024-dashboard"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
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

                                    @if (auth()->user()->hasRole('admin'))
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
                                    @endif
                                    @if (auth()->user()->hasRole('admin'))
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
                                    @endif
                                </ul>
                            </li>

                            @if (auth()->user()->hasRole('admin'))
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
                                    </ul>
                                </li>
                            @endif
                            @if (auth()->user()->hasRole('admin'))
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
                            @endif
                            @if (auth()->user()->hasRole('admin'))
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
                            @endif
                            @if (auth()->user()->hasRole('admin'))
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
                            @endif
                        </ul>
                    </li>
                    @can('read fineds')
                        <li>
                            <a href="{{ route('fineds.list') }}" aria-expanded="false">
                                <i class="fas  fa-ticket"></i>
                                <span class="nav-text">ARAÇ CEZALARI</span>
                            </a>
                        </li>
                    @endcan


                </ul>
            </div>
        </div>
        <!--**********************************
        Sidebar end
    ***********************************-->

        <!--**********************************
        Content body start
    ***********************************-->
        <div class="content-body">
            <div class="container-fluid px-1">
                <!-- row -->
                @if (session()->has('message'))
                    <div class="alert alert-success alert-alt alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                            stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            class="me-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                            <line x1="9" y1="9" x2="9.01" y2="9"></line>
                            <line x1="15" y1="9" x2="15.01" y2="9"></line>
                        </svg>
                        <strong>BAŞARILI!</strong> {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="btn-close"></button>
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-alt alert-dismissible fade show">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                            stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                            class="me-2">
                            <polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                            </polygon>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        <strong>HATA!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible alert-alt solid fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                        </button>
                        @foreach ($errors->all() as $key => $error)
                            {{ $error }}<br />
                            <div class="alert alert-danger alert-alt alert-dismissible fade show">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                    stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                    class="me-2">
                                    <polygon
                                        points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                    </polygon>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                                <strong>HATA!</strong> {{ $error }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="btn-close">
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </div>
        <!--**********************************
        Content body end
    ***********************************-->


        <!--**********************************
        Footer start
    ***********************************-->
        <div class="footer">
            <div class="copyright">
                <p>Copyright © Mustafa KARAGÜLLE © {{ env('APP_VERSION') }} 2024</p>
            </div>
        </div>
        <!--**********************************
        Footer end
    ***********************************-->

    </div>
    <!--**********************************
    Main wrapper end
***********************************-->

    <!--**********************************
    Scripts
***********************************-->

    <!-- Required vendors -->
    @livewireScripts
    <script src="{{ asset('xhtml/vendor/global/global.min.js') }}"></script>
    <x-livewire-alert::flash />
    <script src="{{ asset('xhtml/js/sweetalert2.11.js') }}"></script>
    {{-- <script src="{{asset('vendor/livewire-alert/livewire-alert.js')}}"></script> --}}
    @vite(['resources/js/app.js'])

    @stack('scripts')
    <script>
        $('#main-wrapper').toggleClass("menu-toggle");
        $(".hamburger").toggleClass("is-active");
    </script>
</body>

</html>
