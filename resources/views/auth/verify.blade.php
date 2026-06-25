<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{$webName->value}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset($favicon->path_file) }}">
        <!-- preloader css -->
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" /> --}}
        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        @include('layouts.secure')

    </head>

    <body>
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-lg-3">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">

                                    <div class="auth-content my-auto">
                                        <div class="mb-4 mb-md-5 text-center">
                                            <a href="{{ route('home') }}" class="d-block auth-logo">
                                                <img src="{{ asset($logo->path_file) }}" alt="Logo" class="img-fluid" style="max-height: 80px;">
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h5 class="mb-0 fs-4">Selamat Datang di <span class="text-primary">{{$webName->value}}</span> !</h5>

                                            <h4>Verifikasi Penndaftaran Anda</h4>
                                                <p>Silahkan Klik Button Dibawah ini untuk verifikasi pendaftaran</p>
                                                <div class="mt-4">
                                                    <a href="{{ $whatsappLink }}" target="_blank" class="btn btn-primary w-10">Verifikasi</a>
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">
                                            © Dasbor {{$webName->value}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
                    <div class="col-lg-9">
                        <div class="auth-bg pt-md-5 md:p-4 d-flex" style="background-image: url('{{ asset($authBackground->path_file) }}');">
                            <div class="bg-primary"></div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <!-- pace js -->
        <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>
        <!-- password addon init -->
        <script src="{{ asset('assets/js/pages/pass-addon.init.js') }}"></script>

    </body>

</html>
