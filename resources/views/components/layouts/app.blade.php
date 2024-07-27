<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Jobick : Job Admin Bootstrap 5 Template"/>
    <meta name="format-detection" content="telephone=no">
    <title>OSKA ÇELİK EV - METRAJ PROGRAMI</title>
    <link rel="shortcut icon" type="image/png" href="{{asset('xhtml/images/favicon.png')}}"/>
{{--    @vite(['resources/css/app.css'])--}}
    @stack('styles')
    <link rel="stylesheet" href="{{asset('xhtml/vendor/select2/css/select2.min.css')}}">
    <link href="{{asset('xhtml/css/style.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('xhtml/css/sweetalert.css')}}">
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
        <a href="{{route('dashboard')}}" class="brand-logo">
            <img src="{{asset('xhtml/images/logo-renkli.svg')}}" alt="">
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
                                <img src="{{asset('xhtml/images/profil-foto.jpg')}}" width="20" alt=""/>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                @can('read admins')
                                    <a class="dropdown-item ai-icon fs-6 py-1 btn-sm" href="{{route('admins.list')}}"><i
                                            class="fas fa-user-tie text-danger"></i> <span
                                            class="ms-2">Yöneticiler</span></a>
                                @endcan
                                @can('read roles')
                                    <a class="dropdown-item ai-icon fs-6 py-1 btn-sm" href="{{route('roles.list')}}"><i
                                            class="fas fa-user-secret text-danger"></i> <span class="ms-2">Roller</span></a>
                                @endcan
                                @can('read permissions')
                                    <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                       href="{{route('permissions.list')}}"><i
                                            class="fas fa-fingerprint text-danger"></i> <span
                                            class="ms-2">İzinler</span></a>
                                @endcan
                                @can('read brands')
                                    <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                       href="{{route('brands.list')}}"><i
                                            class="fas fa-user-group text-danger"></i> <span
                                            class="ms-2">Markalar</span></a>
                                @endcan
                                <hr class="my-1"/>
                                @can('read dealers')
                                    <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                       href="{{route('dealers.list')}}"><i
                                            class="fas fa-shop text-danger"></i> <span
                                            class="ms-2">Bayiler</span></a>
                                @endcan

                                @can('read users')
                                    <a class="dropdown-item ai-icon fs-6 py-1 btn-sm"
                                       href="{{route('users.list')}}"><i
                                            class="fas fa-user-group text-danger"></i> <span
                                            class="ms-2">Personeller</span></a>
                                @endcan

                                <hr class="my-1"/>

                                <hr class="my-1"/>
                                <livewire:logout/>
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
                <a class="nav-link " href="javascript:void(0);"  role="button" data-bs-toggle="dropdown">
                    <div class="header-info2 d-flex align-items-center">
                        <img src="{{asset('xhtml/images/profil-foto.jpg')}}" alt="">
                        <div class="d-flex align-items-center sidebar-info">
                            <div>
                                <span class="font-w400 d-block">{{auth()->user()->name??""}}</span>
                                <small class="text-end font-w400">{{\Illuminate\Support\Str::ucfirst(auth()->user()->roles->first()->name??'')}}</small>
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </div>

                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a href="app-profile.html" class="dropdown-item ai-icon ">
                        <svg  xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <span class="ms-2">Profile </span>
                    </a>
                    <a href="email-inbox.html" class="dropdown-item ai-icon">
                        <svg  xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span class="ms-2">Inbox </span>
                    </a>
                    <livewire:logout/>
                </div>
            </div>
            <ul class="metismenu" id="menu">
                <li>
                    <a href="{{route('dashboard')}}" aria-expanded="false">
                        <i class="flaticon-024-dashboard"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
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
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                         stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"></path>
                        <line x1="9" y1="9" x2="9.01" y2="9"></line>
                        <line x1="15" y1="9" x2="15.01" y2="9"></line>
                    </svg>
                    <strong>BAŞARILI!</strong> {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close"></button>
                </div>
            @endif
            @if (session()->has('error'))
                <div class="alert alert-danger alert-alt alert-dismissible fade show">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                         stroke-linecap="round" stroke-linejoin="round" class="me-2">
                        <polygon
                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                    <strong>HATA!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                    </button>
                </div>
            @endif
            {{$slot??''}}
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
            <p>Copyright © Mustafa KARAGÜLLE © {{env('APP_VERSION')}} 2024</p>
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
{{--@vite(['resources/js/app.js'])--}}
@livewireScripts
<script src="{{asset('xhtml/vendor/global/global.min.js')}}"></script>
<script src="{{asset('xhtml/js/sweetalert2.11.js')}}"></script>
<script src="{{asset('vendor/livewire-alert/livewire-alert.js')}}"></script>
<script src="{{asset('xhtml/vendor/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('xhtml/js/powergrid.js')}}"></script>
<x-livewire-alert::flash />
@stack('scripts')
<script>
    $('#main-wrapper').toggleClass("menu-toggle");
    $(".hamburger").toggleClass("is-active");
</script>
</body>
</html>
