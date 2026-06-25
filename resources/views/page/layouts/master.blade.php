<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="all, index, follow">
        <meta name="author" content="{{ $webName->value }}">
        <meta name="format-detection" content="telephone={{ $nomor->value }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta http-equiv="Content-Language" content="id-ID">


        <!-- SEO Meta -->
        <meta name="description" content="@yield('meta_description', 'Website Resmi Dinas Kesehatan Kota Bandar Lampung. Menyediakan informasi layanan kesehatan, berita kesehatan, program pemerintah, agenda kegiatan, serta edukasi kesehatan bagi masyarakat Kota Bandar Lampung.')">

        <meta name="keywords" content="Dinas Kesehatan Bandar Lampung, Dinkes Bandar Lampung, Kesehatan Kota Bandar Lampung, layanan kesehatan Bandar Lampung, informasi kesehatan Bandar Lampung, puskesmas Bandar Lampung, program kesehatan, berita kesehatan Bandar Lampung">

        <meta name="author" content="Dinas Kesehatan Kota Bandar Lampung">

        <!-- Canonical URL -->
        <link rel="canonical" href="{{ url()->current() }}">

        <meta property="og:locale" content="id_ID"/>

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="{{ url()->current() }}"/>

        <meta property="og:title" content="@yield('og_title', 'Dinas Kesehatan Kota Bandar Lampung')"/>

        <meta property="og:description" content="@yield('og_description', 'Website Resmi Dinas Kesehatan Kota Bandar Lampung yang menyediakan informasi layanan kesehatan, program kesehatan masyarakat, berita kesehatan, dan agenda kegiatan kesehatan.')"/>

        <meta property="og:image" content="@yield('og_image', url($favicon->path_file))"/>

        <meta property="og:site_name" content="Dinas Kesehatan Kota Bandar Lampung"/>

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">

        <meta name="twitter:title" content="@yield('twitter_title', 'Dinas Kesehatan Kota Bandar Lampung')">

        <meta name="twitter:description" content="@yield('twitter_description', 'Website resmi Dinas Kesehatan Kota Bandar Lampung yang menyediakan informasi layanan kesehatan, program kesehatan, dan berita kesehatan terkini.')">

        <meta name="twitter:image" content="@yield('twitter_image', url($favicon->path_file))">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
        <link href="{{ asset('dinkes/assets/css/theme.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('dinkes/assets/css/style.css?v='.time()) }}" rel="stylesheet" type="text/css" />


        <!-- Structured Data -->
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "GovernmentOrganization",
            "name": "Portal Dinas Kesehatan Kota Bandar Lampung",
            "url": "https://{{ $domain->value }}",
            "logo": "{{ url($favicon->path_file) }}",
            "sameAs": [
            @foreach($sosialmedia as $index => $sosialicon)
                "{{ $sosialicon->url }}"@if(!$loop->last),@endif
            @endforeach
            ]
        }
        </script>

        <title>@yield('meta_title', $webName->value)</title>
        <!-- Favicon -->
        <link rel="icon" href="{{ url($favicon->path_file) }}" type="image/x-icon">



    </head>
    <body>
            <div class="topbar">
                <div class="container">
                    <div class="topbar-wrapper">
                    <div class="topbar-left">
                        <span class="material-icons">public</span> {{ $webName->value }}
                    </div>
                    <div class="topbar-right">
                        <span>
                        <span class="material-icons">visibility</span> Online {{ $onlineUsers }} </span>
                        <span>
                        <span class="material-icons">today</span> Hari ini {{ $totalVisitorsToday }} </span>
                        <span>
                        <span class="material-icons">bar_chart</span> Total {{ $totalVisitors }} </span>
                    </div>
                    </div>
                </div>
            </div>
            <nav class="navbar navbar-expand-lg sticky-top bg-white shadow-sm">
                <div class="container">
                    <a href="{{route('home')}}" class="navbar-brand d-flex align-items-center">
                    <img src="{{ asset($logo->path_file) }}" class="me-2" width="200" height="55"> </a>
                    <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#mainMenu">
                    <span class="material-icons">menu</span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainMenu">
                    <button class="menu-close d-lg-none" data-bs-toggle="collapse" data-bs-target="#mainMenu">
                        <span class="material-icons">close</span>
                    </button>
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                        </li>
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown"> Profil </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{route('profile.pegawai')}}" class="dropdown-item">Pegawai</a>
                            </li>
                            <li>
                                <a  href="{{ route('user.profile', 'struktur-organisasi') }}" class="dropdown-item">Struktur Organisasi</a>
                            </li>
                            <li>
                                <a href="{{ route('user.profile', 'tugas-dan-fungsi') }}" class="dropdown-item">Tugas & Fungsi</a>
                            </li>
                        </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('user.berita')}}" class="nav-link">Berita</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link">Galeri</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link">Kontak</a>
                        </li>
                        <li class="nav-item ms-lg-3 d-none d-lg-block">
                        <div class="search-box">
                            <input type="text" placeholder="Masukkan pencarian disini">
                            <button>
                            <span class="material-icons">search</span>
                            </button>
                        </div>
                        </li>
                    </ul>
                    <div class="mobile-search d-lg-none">
                        <input type="text" placeholder="Masukkan pencarian disini">
                        <button>
                        <span class="material-icons">search</span>
                        </button>
                    </div>
                    </div>
                </div>
            </nav>

        <main class="main-content">

            {{-- Main Content --}}
            @yield('konten')



        </main>

        <footer class="main-footer">
            <div class="container">
                <div class="row gy-4">

                <!-- Kolom 1: Informasi Kontak -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-info">
                    <h5 class="footer-heading">
                        <span class="material-icons">location_on</span> Alamat Kantor
                    </h5>
                    <p class="footer-desc">
                        {{ $alamat->value }}
                    </p>

                    <h5 class="footer-heading mt-4">
                        <span class="material-icons">access_time</span> Jam Operasional
                    </h5>
                    <p class="footer-desc">Senin - Jumat: 08.00 - 15.30 WIB</p>
                    </div>
                </div>

                <!-- Kolom 2: Lokasi (Google Maps) -->
                <div class="col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="map-box">
                    <iframe src="{{ $maps->value }}" width="100%" height="180" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>

                <!-- Kolom 3: Statistik (Rata Kanan) -->
                <div class="col-lg-4 col-md-12 text-lg-end d-flex flex-column align-items-lg-end">
                    <h5 class="footer-heading">Statistik Pengunjung</h5>

                    <div class="stat-container mt-3">
                    <div class="stat-main">
                        <span class="stat-label">Total Pengunjung</span>
                        <div class="stat-number">{{ $totalVisitors }}</div>
                    </div>

                    <div class="stat-secondary mt-3">
                        <div class="stat-item-small">
                        <span class="label">Hari Ini</span>
                        <span class="badge-count">{{ $totalVisitorsToday }}</span>
                        </div>
                        <div class="stat-item-small mt-2">
                        <span class="label">Online Sekarang</span>
                        <span class="badge-count online-dot">{{ $onlineUsers }}</span>
                        </div>
                    </div>
                    </div>
                </div>

                </div>
            </div>

            <!-- Copyright -->
            <div class="footer-copyright">
                <div class="container text-center">
                <p>© 2026 <span class="fw-bold">Pemerintah Kota Bandar Lampung</span>. Seluruh Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </footer>


        <div class="scroll-top" id="scrollTop">
            <svg class="scroll-progress" width="52" height="52" viewBox="0 0 100 100">
                <circle cx="50" cy="50" r="45"></circle>
            </svg>
            <span class="material-icons">keyboard_arrow_up</span>
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

        <!-- Datatable init js -->
        <script src="{{ asset('dinkes/assets/js/script.js?v='.time()) }}"></script>

        {{-- Libs JS --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


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
