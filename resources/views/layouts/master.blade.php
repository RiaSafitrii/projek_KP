<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Dasbor | {{$webName->value}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset($favicon->path_file) }}">

    @stack('styles')


    <!-- plugin css -->
    <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css" />
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Kalender -->
    <link href="{{ asset('assets/libs/@fullcalendar/core/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/@fullcalendar/daygrid/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/@fullcalendar/bootstrap/main.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/@fullcalendar/timegrid/main.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- preloader css -->
    @hasSection('preloader_css')
        @yield('preloader_css')
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" />
    @endif

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" /> --}}
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- choices css -->
    <link href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- Custom --}}
    <link href="{{ asset('assets/css/custom.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom2.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    {{-- Jquery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.10.0/full-all/ckeditor.js"></script>

    {{-- select 2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Highchart --}}
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <link href="{{ asset('assets/libs/summernote/css/summernote-bs4.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/libs/summernote/js/summernote-bs4.min.js') }}"></script>


    <!-- Icon Fontawesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    {{-- @include('layouts.secure') --}}

</head>

<style>
    .thumbnail-container {
        width: 100%; /* Sesuaikan dengan lebar kolom */
        height: 300px; /* Tinggi thumbnail yang diinginkan */
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .thumbnail-image {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Memastikan gambar terpotong sesuai proporsi kontainer */
    }

</style>

<body>
<!-- <body data-layout="horizontal"> -->
    <!-- Begin page -->
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="#" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset($favicon->path_file) }}" alt="" height="35">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset($logo->path_file) }}" alt="" height="50">
                            </span>
                        </a>

                        <a href="#" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset($favicon->path_file) }}" alt="" height="35">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset($logo->path_file) }}" alt="" height="50">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>

                    <!-- Role-->
                    @if(Auth::check() && Auth::user()->role == null)
                        <?php
                        Auth::logout();
                        ?>
                    @endif
                    <form class="app-search d-none d-lg-block">
                        <div class="position-relative">
                            <h3><span class="badge bg-info text-white">{{ ucwords(Auth::user()->role == 'admin' ? 'Super Admin' :
                            (Auth::user()->role == 'operator' ? 'Admin' : Auth::user()->role)) }}</span></h3>
                        </div>
                    </form>
                </div>

                <div class="d-flex">

                    <div class="dropdown d-none d-sm-inline-block">
                        <button type="button" class="btn header-item" id="mode-setting-btn">
                            <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                            <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                        </button>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item right-bar-toggle me-2">
                            <i data-feather="settings" class="icon-lg"></i>
                        </button>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item bg-light-subtle border-start border-end" id="page-header-user-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/userbg.png') }}"
                                alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->name }}</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#newpassword"><i class="mdi mdi-key-alert-outline font-size-16 align-middle me-1"></i> Ganti Kata Sandi</a>
                            <hr>
                            <a class="dropdown-item" href="" data-bs-toggle="modal" data-bs-target="#logout"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Keluar</a>

                        </div>
                    </div>

                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->

                    {{-- Admin --}}
                    @if(Auth::user()->role == 'admin')
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dasbor</span>
                                </a>
                            </li>
                            <li class="menu-title" data-key="t-menu">Master Data</li>
                            <li>
                                <a href="{{ route('user.index') }}">
                                    <i data-feather="users"></i>
                                    <span>Kelola Pengguna</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('penulis.index') }}">
                                    <i class="mdi mdi-account-child-outline"></i>
                                    <span>Kelola Penulis</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Master Post</li>
                            <li>
                                <a href="{{ route('storage.index') }}">
                                    <i class="mdi mdi-zip-disk"></i>
                                    <span>Penyimpanan</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categories.index') }}">
                                    <i class="mdi mdi-file-tree-outline"></i>
                                    <span>Kategori</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mstnews.index') }}">
                                    <i class="mdi mdi-newspaper-variant-multiple"></i>
                                    <span>Konten</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('comment') }}">
                                    <i class="mdi mdi-comment-text"></i>
                                    <span>Komentar</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('mstgalery.index') }}">
                                    <i class="mdi mdi-folder-multiple-image"></i>
                                    <span>Galeri</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mstslider.index') }}">
                                    <i class="mdi mdi-image-area"></i>
                                    <span>Slider</span>
                                </a>
                            </li>

                            <li class="menu-title" data-key="t-menu">Master Information</li>

                            <li>
                                <a href="{{ route('mstdatapegawai.index') }}">
                                    <i class="mdi mdi-account-group-outline"></i>
                                    <span>Data Pegawai</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mstdatapuskesmas.index') }}">
                                    <i class="mdi mdi-hospital-building"></i>
                                    <span>Data Puskesmas</span>
                                </a>
                            </li>


                            <li class="menu-title" data-key="t-menu">Navigasi</li>
                            <li>
                                <a href="javascript: void(0);" class="has-arrow">
                                    <i data-feather="file-text"></i>
                                    <span data-key="t-pages">Profil</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('mststrukturorganisasi.index') }}" data-key="t-starter-page">Struktur Organisasi</a></li>
                                    <li><a href="{{ route('msttugasfungsi.index') }}" data-key="t-starter-page">Tugas & Fungsi</a></li>


                                </ul>
                            </li>



                            {{-- <li>
                                <a href="{{ route('mstcontactus.index') }}">
                                    <i class="mdi mdi-chat-processing-outline"></i>
                                    <span>Hubungi Kami</span>
                                </a>
                            </li> --}}



                            <li class="menu-title" data-key="t-menu">Konfigurasi</li>

                            <li>
                                <a href="{{ route('mstoption.index') }}">
                                    <i class="mdi mdi-console-network"></i>
                                    <span>Pengaturan Web</span>
                                </a>
                            </li>
                            {{-- <li>
                                <a href="{{ route('mstchatadmin.index') }}">
                                    <i class="mdi mdi-whatsapp"></i>
                                    <span>Chat Admin</span>
                                </a>
                            </li> --}}
                            <li>
                                <a href="{{ route('mstsosialmedia.index') }}">
                                    <i class="mdi mdi-checkbox-intermediate"></i>
                                    <span>Media Sosial</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mstkelolanavigasi.index') }}">
                                    <i class="mdi mdi-card-bulleted-settings-outline"></i>
                                    <span>Kelola Navigasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('msthaknavigasi.index') }}">
                                    <i class="mdi mdi-account-key-outline"></i>
                                    <span>Hak Akses Navigasi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('msttahun.index') }}">
                                    <i class="mdi mdi-calendar-blank"></i>
                                    <span>Tahun</span>
                                </a>
                            </li>


                        </ul>
                    @endif

                    @if(Auth::user()->role == 'operator')
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dasbor</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('mstoption.index') }}">
                                    <i class="mdi mdi-console-network"></i>
                                    <span>Pengaturan Web</span>
                                </a>
                            </li>


                            @if(isset($groupedNav) && $groupedNav->isNotEmpty())
                                @foreach($groupedNav as $groupName => $navigations)
                                    <li class="menu-title" data-key="t-menu">{{ $groupName }}</li>
                                        @foreach($navigations as $nav)
                                            {!! $nav->value !!}
                                        @endforeach
                                @endforeach
                            @endif
                        </ul>



                    @endif

                    @if(Auth::user()->role == 'user')
                    <ul class="metismenu list-unstyled" id="side-menu">
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i data-feather="home"></i>
                                    <span data-key="t-dashboard">Dasbor</span>
                                </a>
                            </li>

                    </ul>
                    @endif

                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <!-- Start Page-content -->
            @yield('konten')
            <!-- End Page-content -->


            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            © Dasbor {{$webName->value}} 2025
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

        {{-- Modal Logout --}}
        <div class="modal fade" id="logout" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Keluar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Pilih "Keluar" di bawah jika Anda siap untuk mengakhiri sesi saat ini.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                        <form action="{{ route('logout') }}" id="formlogout" method="POST" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-logout label-icon"></i>Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Ganti Kata Sandi --}}
        <div class="modal fade" id="newpassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Kata Sandi Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('newpassword') }}" id="formnewpassword" method="POST">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-label">Kata Sandi Lama</label>
                                    </div>
                                </div>

                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password" class="form-control" placeholder="Masukkan kata sandi" aria-label="Password" aria-describedby="password-addon" name="old_password" autocomplete="new-password" required
                                    {{-- pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$" --}}
                                    {{-- title="Kata sandi harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter khusus." --}}
                                    >
                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-old"><i class="mdi mdi-eye-outline"></i></button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="flex-grow-1">
                                        <label class="form-label">Kata Sandi Baru</label>
                                    </div>
                                </div>

                                <div class="input-group auth-pass-inputgroup">
                                    <input type="password" class="form-control" placeholder="Masukkan kata sandi" aria-label="Password" aria-describedby="password-addon" name="password" autocomplete="new-password" required
                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                    title="Kata sandi harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter khusus.">
                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-new"><i class="mdi mdi-eye-outline"></i></button>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>

                            <button type="submit" class="btn btn-info waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-update label-icon"></i>Perbarui</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>
    <!-- END layout-wrapper -->


    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title d-flex align-items-center p-3">

                <h5 class="m-0 me-2">Kustomisasi Tema</h5>

                <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
            </div>

            <!-- Pengaturan -->
            <hr class="m-0" />

            <div class="p-4">
                <h6 class="mb-3">Tata Letak</h6>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout"
                        id="layout-vertical" value="vertical">
                    <label class="form-check-label" for="layout-vertical">Vertikal</label>
                </div>
                <div class="form-check form-check-inline">
                    {{-- <input class="form-check-input" type="radio" name="layout"
                        id="layout-horizontal" value="horizontal">
                    <label class="form-check-label" for="layout-horizontal">Horizontal</label> --}}
                </div>

                <h6 class="mt-4 mb-3 pt-2">Mode Tata Letak</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-mode"
                        id="layout-mode-light" value="light">
                    <label class="form-check-label" for="layout-mode-light">Terang</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-mode"
                        id="layout-mode-dark" value="dark">
                    <label class="form-check-label" for="layout-mode-dark">Gelap</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Lebar Tata Letak</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-width"
                        id="layout-width-fuild" value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                    <label class="form-check-label" for="layout-width-fuild">Fluid</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-width"
                        id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed')">
                    <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Posisi Tata Letak</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-position"
                        id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                    <label class="form-check-label" for="layout-position-fixed">Tetap</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-position"
                        id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                    <label class="form-check-label" for="layout-position-scrollable">Dapat Digulir</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Warna Topbar</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="topbar-color"
                        id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                    <label class="form-check-label" for="topbar-color-light">Terang</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="topbar-color"
                        id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                    <label class="form-check-label" for="topbar-color-dark">Gelap</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Ukuran Sidebar</h6>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-size"
                        id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                    <label class="form-check-label" for="sidebar-size-default">Default</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-size"
                        id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                    <label class="form-check-label" for="sidebar-size-compact">Kompak</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-size"
                        id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                    <label class="form-check-label" for="sidebar-size-small">Kecil (Tampilan Ikon)</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Warna Sidebar</h6>

                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-color"
                        id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                    <label class="form-check-label" for="sidebar-color-light">Terang</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-color"
                        id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                    <label class="form-check-label" for="sidebar-color-dark">Gelap</label>
                </div>
                <div class="form-check sidebar-setting">
                    <input class="form-check-input" type="radio" name="sidebar-color"
                        id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                    <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                </div>

                <h6 class="mt-4 mb-3 pt-2">Arah</h6>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="layout-direction"
                        id="layout-direction-ltr" value="ltr">
                    <label class="form-check-label" for="layout-direction-ltr">LTR</label>
                </div>
                <div class="form-check form-check-inline">
                    {{-- <input class="form-check-input" type="radio" name="layout-direction"
                        id="layout-direction-rtl" value="rtl">
                    <label class="form-check-label" for="layout-direction-rtl">RTL</label> --}}
                </div>

            </div>

        </div> <!-- end slimscroll-menu-->
    </div>

    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>


    <script>
        function hanyaAngka(evt) {
            var charCode = (evt.which) ? evt.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
    </script>
    <script>
        function formatRupiah(element) {
            // Ambil nilai input
            let value = element.value.replace(/[^,\d]/g, ''); // Hapus karakter selain angka dan koma
            const split = value.split(',');
            const sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // Tambahkan titik jika ribuan
            if (ribuan) {
                const separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            // Tambahkan sisa koma jika ada
            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;

            // Update nilai input
            element.value = rupiah;
        }
    </script>
    <script>
        function rangeAngka(input) {
            let value = input.value;

            // Menghapus karakter selain angka dan memeriksa input
            value = value.replace(/[^0-9]/g, '');

            // Menangani angka yang dimulai dengan 0, kecuali angka 0 sendiri
            if (value.length > 1 && value.startsWith('0')) {
                value = value.substring(1); // Menghapus angka 0 di depan
            }

            // Mengatur jika input hanya 0
            if (value === '0') {
                value = ''; // Menghapus jika 0 dimasukkan
            }

            // Batasi agar tidak lebih dari 100
            if (parseInt(value) > 100) {
                value = '100';
            }

            // Memperbarui nilai input
            input.value = value;
        }
    </script>


<script>
    // Fungsi untuk toggle password visibility pada input lama
    document.getElementById('password-old').addEventListener('click', function() {
        var input = this.previousElementSibling;  // Mengambil input sebelum tombol
        if (input.type === 'password') {
            input.type = 'text'; // Tampilkan password
            this.innerHTML = '<i class="mdi mdi-eye-off-outline"></i>';  // Ubah ikon menjadi "eye-off"
        } else {
            input.type = 'password'; // Sembunyikan password
            this.innerHTML = '<i class="mdi mdi-eye-outline"></i>';  // Ubah ikon menjadi "eye"
        }
    });

    // Fungsi untuk toggle password visibility pada input baru
    document.getElementById('password-new').addEventListener('click', function() {
        var input = this.previousElementSibling;  // Mengambil input sebelum tombol
        if (input.type === 'password') {
            input.type = 'text'; // Tampilkan password
            this.innerHTML = '<i class="mdi mdi-eye-off-outline"></i>';  // Ubah ikon menjadi "eye-off"
        } else {
            input.type = 'password'; // Sembunyikan password
            this.innerHTML = '<i class="mdi mdi-eye-outline"></i>';  // Ubah ikon menjadi "eye"
        }
    });

</script>






    <!-- JAVASCRIPT -->
    {{-- <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script> --}}
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    @stack('scripts')

    <!-- pace js -->
    <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- Plugins js-->



    <!-- dashboard init -->
    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>
    <!-- Calendar -->
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/libs/@fullcalendar/core/main.min.js') }}"></script>
    <script src="{{ asset('assets/libs/@fullcalendar/bootstrap/main.min.js') }}"></script>
    <script src="{{ asset('assets/libs/@fullcalendar/daygrid/main.min.js') }}"></script>
    <script src="{{ asset('assets/libs/@fullcalendar/timegrid/main.min.js') }}"></script>
    <script src="{{ asset('assets/libs/@fullcalendar/interaction/main.min.js') }}"></script>

    <!-- Calendar init -->
    <script src="{{ asset('assets/js/pages/calendar.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- choices js -->
    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- init js -->
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <!-- fontawesome icons init -->
    <script src="{{ asset('assets/js/pages/fontawesome.init.js') }}"></script>


    <!-- Custom -->
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <!-- password addon init -->
    <script src="{{ asset('assets/js/pages/pass-addon.init.js') }}"></script>

    <script>
        document.addEventListener('play', function(e) {
            const mediaElements = document.querySelectorAll('audio, video');
            mediaElements.forEach((el) => {
                if (el !== e.target) {
                    el.pause();
                }
            });
        }, true);
    </script>

</body>

</html>
