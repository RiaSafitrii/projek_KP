<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>New Password | {{$webName->value}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/logo.jpg') }}">
        <!-- preloader css -->
        {{-- <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}" type="text/css" /> --}}
        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body>
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-3 col-lg-4 col-md-5">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">

                                    <div class="auth-content my-auto">
                                        <div class="mb-4 mb-md-5 text-center">
                                            <a href="index.html" class="d-block auth-logo">
                                                <img src="{{ asset('logo.png') }}" alt="" height="80">
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h5 class="mb-0">Atur Ulang Kata Sandi</h5>
                                            <p class="text-muted mt-2">Atur Ulang Kata Sandi untuk Diingat.</p>

                                            @if (session('success'))
                                                <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                                                    <i class="mdi mdi-check-all label-icon"></i><strong>Sukses</strong> - {{ session('success') }}
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
                                        <div class="alert alert-warning text-center my-4" role="alert">
                                            Masukkan kata sandi baru Anda!
                                        </div>
                                        <form action="{{ route('password.update') }}" id="login" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $token }}">
                                            <input type="hidden" class="form-control" id="email" name="email" value="{{ old('email', $email) }}" required autofocus>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <label class="form-label">Kata Sandi</label>
                                                    </div>
                                                </div>

                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control" placeholder="Masukan Kata Sandi" aria-label="Kata Sandi" aria-describedby="password-addon" name="password" id="password">
                                                    <button class="btn btn-light shadow-none ms-0" type="button" onclick="togglePasswordVisibility('password', this)"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <label class="form-label">Konfirmasi Kata Sandi</label>
                                                    </div>
                                                </div>

                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control" placeholder="Masukan Konfirmasi Kata Sandi" aria-label="Konfirmasi Kata Sandi" aria-describedby="password-addon" name="password_confirmation" id="password_confirmation" onkeyup="checkPasswordMatch()">
                                                    <button class="btn btn-light shadow-none ms-0" type="button" onclick="togglePasswordVisibility('password_confirmation', this)"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                            </div>

                                            <!-- Peringatan ketika password tidak cocok -->
                                            <div id="passwordWarning" class="text-danger m-2" style="display: none;">Kata sandi tidak cocok.</div>

                                            <script>
                                                function togglePasswordVisibility(inputId, btn) {
                                                    const input = document.getElementById(inputId);
                                                    const icon = btn.querySelector('i');

                                                    if (input.type === 'password') {
                                                        input.type = 'text';
                                                        icon.classList.remove('mdi-eye-outline');
                                                        icon.classList.add('mdi-eye-off-outline');
                                                    } else {
                                                        input.type = 'password';
                                                        icon.classList.remove('mdi-eye-off-outline');
                                                        icon.classList.add('mdi-eye-outline');
                                                    }
                                                }

                                                function checkPasswordMatch() {
                                                    const password = document.getElementById('password').value;
                                                    const confirmPassword = document.getElementById('password_confirmation').value;
                                                    const warning = document.getElementById('passwordWarning');

                                                    if (password !== confirmPassword) {
                                                        warning.style.display = 'block'; // Tampilkan pesan peringatan
                                                    } else {
                                                        warning.style.display = 'none'; // Sembunyikan pesan peringatan
                                                    }
                                                }
                                            </script>


                                            <div class="mb-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit" name="sb">Atur Ulang Kata Sandi</button>
                                            </div>
                                        </form>
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
                    <div class="col-xxl-9 col-lg-8 col-md-7">
                        <div class="auth-bg pt-md-5 p-4 d-flex" style="background-image: url('{{ asset('assets/images/auth-bg.jpg') }}');">
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
