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
        <!-- choices css -->
        <link href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />
        {{-- Jquery --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        {{-- select 2 --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
                                            <p class="text-muted mt-2">Daftar Untuk Melanjutkan</p>

                                            @include('layouts.alert')

                                        </div>
                                        <form action="{{ route('postregister') }}" id="register" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="opd_id" class="form-label">Asal OPD</label><label style="color: darkred">*</label>
                                                <select class="form-control" data-trigger name="opd_id" id="opd_id" required>
                                                    <option value="" disabled selected>-- Pilih OPD --</option>
                                                    @foreach($opd as $dopd)
                                                        <option value="{{ $dopd->id }}">{{ $dopd->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nama</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Masukan Nama" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">NIP</label>
                                                <input type="text" class="form-control" name="nip" id="nip" maxlength="18" onkeypress="return hanyaAngka(event)" placeholder="Masukan NIP" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Nomor WA</label>
                                                <input type="text" class="form-control" name="no_wa" id="no_wa" maxlength="18" onkeypress="return hanyaAngka(event)" placeholder="Masukan Nomor WA" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control" name="email" id="username" placeholder="Masukan Email" required>
                                            </div>
                                            <div class="mb-3">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <label class="form-label">Password</label>
                                                    </div>
                                                </div>

                                                <div class="input-group auth-pass-inputgroup">
                                                    <input type="password" class="form-control" placeholder="Masukan password" aria-label="Password" aria-describedby="password-addon" autocomplete="new-password" name="password" id="password" required>
                                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                </div>
                                                <div id="password-feedback" class="text-danger mt-2" style="display: none;">
                                                    <ul>
                                                        <li id="length-warning">Kata sandi minimal terdiri dari 8 karakter.</li>
                                                        <li id="lowercase-warning">Kata sandi harus mengandung setidaknya satu huruf kecil.</li>
                                                        <li id="uppercase-warning">Kata sandi harus mengandung setidaknya satu huruf besar.</li>
                                                        <li id="number-warning">Kata sandi harus mengandung setidaknya satu angka.</li>
                                                        <li id="special-char-warning">Kata sandi harus mengandung setidaknya satu karakter khusus.</li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit" id="submit-button" name="sb">Daftar</button>
                                            </div>
                                        </form>
                                            <div class="mt-5 text-center">
                                                <p class="text-muted mb-0">Sudah Memiliki Akun ? <a href="{{ route('login') }}" class="text-primary fw-semibold"> Masuk </a> </p>
                                            </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const passwordInput = document.getElementById('password');
                                                const feedback = document.getElementById('password-feedback');

                                                const lengthWarning = document.getElementById('length-warning');
                                                const lowercaseWarning = document.getElementById('lowercase-warning');
                                                const uppercaseWarning = document.getElementById('uppercase-warning');
                                                const numberWarning = document.getElementById('number-warning');
                                                const specialCharWarning = document.getElementById('special-char-warning');

                                                passwordInput.addEventListener('input', function() {
                                                    const password = passwordInput.value;

                                                    // Show feedback
                                                    feedback.style.display = 'block';

                                                    // Validasi panjang password
                                                    if (password.length >= 8) {
                                                        lengthWarning.style.display = 'none';
                                                    } else {
                                                        lengthWarning.style.display = 'block';
                                                    }

                                                    // Validasi huruf kecil
                                                    const hasLowercase = /[a-z]/.test(password);
                                                    if (hasLowercase) {
                                                        lowercaseWarning.style.display = 'none';
                                                    } else {
                                                        lowercaseWarning.style.display = 'block';
                                                    }

                                                    // Validasi huruf besar
                                                    const hasUppercase = /[A-Z]/.test(password);
                                                    if (hasUppercase) {
                                                        uppercaseWarning.style.display = 'none';
                                                    } else {
                                                        uppercaseWarning.style.display = 'block';
                                                    }

                                                    // Validasi angka
                                                    const hasNumber = /[0-9]/.test(password);
                                                    if (hasNumber) {
                                                        numberWarning.style.display = 'none';
                                                    } else {
                                                        numberWarning.style.display = 'block';
                                                    }

                                                    // Validasi karakter khusus
                                                    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
                                                    if (hasSpecialChar) {
                                                        specialCharWarning.style.display = 'none';
                                                    } else {
                                                        specialCharWarning.style.display = 'block';
                                                    }

                                                    // Disable submit jika password tidak valid
                                                    const isValid = password.length >= 8 && hasLowercase && hasUppercase && hasNumber && hasSpecialChar;
                                                    document.getElementById('submit-button').disabled = !isValid;
                                                });
                                            });

                                        </script>
                                        <script>
                                            document.getElementById('register').addEventListener('submit', function(event) {
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
                                            © {{$webName->value}}
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

        <script>
            function hanyaAngka(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            }
        </script>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
        <!-- choices js -->
        <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
        <!-- init js -->
        <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
        <!-- pace js -->
        <script src="{{ asset('assets/libs/pace-js/pace.min.js') }}"></script>
        <!-- Required datatable js -->
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <!-- password addon init -->
        <script src="{{ asset('assets/js/pages/pass-addon.init.js') }}"></script>

    </body>

</html>
