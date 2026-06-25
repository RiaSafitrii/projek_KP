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
                                            <p class="text-muted mt-2">Masuk Untuk Melanjutkan</p>

                                            @if (session('success'))
                                                <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                                    <i class="mdi mdi-check-all label-icon"></i><strong>Berhasil</strong> - {{ session('success') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            @endif
                                            @if (session('fail'))
                                                <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                                    <i class="mdi mdi-block-helper label-icon"></i><strong>Gagal</strong> - {{ session('fail') }}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            @endif

                                        </div>
                                        <form action="{{ route('postlogin') }}" id="login" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">Email / Username</label>
                                                <input type="text" class="form-control" name="email" id="username" placeholder="Enter email / username" required>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <label class="form-label">Password</label>
                                                    </div>
                                                </div>

                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon" name="password" required>
                                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit" name="sb">Masuk</button>
                                            </div>

                                        </form>
                                        <div class="mt-5 text-center">
                                            <p class="text-muted mb-0">Belum Memiliki Akun ? <a href="{{ route('register') }}" class="text-primary fw-semibold"> Daftar Sekarang </a> </p>
                                        </div>
                                        <script>
                                            document.getElementById('login').addEventListener('submit', function(event) {
                                                if (!this.checkValidity()) {
                                                    event.preventDefault(); // Prevent form submission if it's not valid
                                                    return false;
                                                }
                                                var submitButton = this.querySelector('button[name="sb"]');
                                                submitButton.disabled = true;
                                                submitButton.textContent   = 'Please Wait...';
                                                return true; // Allow form submission
                                            });
                                        </script>
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
