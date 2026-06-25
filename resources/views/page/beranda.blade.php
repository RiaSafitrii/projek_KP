@extends('page.layouts.master')
@section('konten')

<style>

    .menu-card {
        border: none;
        border-radius: 24px; /* Sudut lebih bulat agar terlihat kekinian */
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        background: #ffffff;
        padding: 2.5rem 1.5rem;
        height: 100%;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        position: relative;
        z-index: 1;
        cursor: pointer; /* Menandakan elemen bisa diklik */
        text-decoration: none !important;
    }

    /* Efek saat kursor diarahkan ke kartu */
    .menu-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(13, 110, 253, 0.12); /* Bayangan biru halus */
    }

    .menu-card:active {
        transform: scale(0.95);
        transition: 0.1s;
    }

    /* Wadah Ikon */
    .icon-box {
        width: 70px;
        height: 70px;
        background-color: #f1f7ff; /* Latar belakang ikon yang soft */
        color: #0d6efd; /* Warna biru Bootstrap */
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        transition: 0.3s;
    }

    .menu-card:hover .icon-box {
        background-color: #0d6efd;
        color: #ffffff;
        transform: rotate(-5deg) scale(1.1); /* Sedikit rotasi untuk kesan dinamis */
    }

    /* Tipografi */
    .menu-title {
        font-weight: 700;
        color: #2d3436;
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .menu-desc {
        font-size: 0.95rem;
        color: #636e72;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* Dekorasi Garis Halus di Bawah */
    .menu-card::after {
        content: '';
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 4px;
        background: #0d6efd;
        border-radius: 10px;
        opacity: 0.3;
        transition: 0.3s;
    }

    .menu-card:hover::after {
        width: 60px;
        opacity: 1;
    }
</style>

<div class="container mt-3">
    <div id="heroSlider" class="carousel slide carousel-fade" data-bs-ride="carousel">

        <!-- INDIKATOR (DOTS) -->
        <div class="carousel-indicators">
            <button type="button"
                    data-bs-target="#heroSlider"
                    data-bs-slide-to="0"
                    class="active"
                    aria-current="true"
                    aria-label="Slide Utama}">
            </button>
            @foreach($slider as $index => $item)
                <button type="button"
                        data-bs-target="#heroSlider"
                        data-bs-slide-to="{{ $index+1 }}"
                        aria-label="{{ $item->name }}">
                </button>
            @endforeach
        </div>

        <div class="carousel-inner shadow-sm"> <!-- Tambah shadow halus -->

            <div class="carousel-item active" data-bs-interval="5000">
                <div class="custom-slider-ratio">
                    <img src="{{ asset($sliderutama->path_file) }}" class="d-block me-auto ms-auto" alt="{{ $sliderutama->name ?? 'Slider' }}">
                </div>
            </div>

            @foreach($slider as $index => $item)
                <div class="carousel-item" data-bs-interval="5000">
                    <div class="custom-slider-ratio">
                        <img src="{{ asset($item->path_file) }}" class="d-block me-auto ms-auto" alt="{{ $item->name ?? 'Slider' }}">
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tombol Panah (Opsional tapi disarankan) -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>


<div class="container py-5">
    <div class="row g-4 justify-content-center">

        <div class="col-md-3">
            <div class="menu-card">
                <div class="icon-box">
                    <span class="material-icons">medical_services</span>
                </div>
                <h5 class="menu-title">Layanan Publik</h5>
                <p class="menu-desc">Informasi Perizinan, Sertifikasi, dan Layanan Kesehatan.</p>
            </div>
        </div>

        <div class="col-md-3">
            <a href="{{route('user.datapuskesmas')}}" style="text-decoration: none;">
            <div class="menu-card">
                <div class="icon-box">
                    <span class="material-icons">location_on</span>
                </div>
                <h5 class="menu-title">Puskesmas</h5>
                <p class="menu-desc">Cek lokasi Puskesmas se-Bandar Lampung.</p>
            </div>
            </a>
        </div>

        <div class="col-md-3">
            <div class="menu-card">
                <div class="icon-box">
                    <span class="material-icons">analytics</span>
                </div>
                <h5 class="menu-title">Data Kesehatan</h5>
                <p class="menu-desc">Statistik kesehatan, Stunting, dan Indikator Kota Sehat.</p>
            </div>
        </div>
    </div>
</div>



@if(!empty($berita))
<section class="mt-4 mb-4" id="artikel">
    <div class="container">
        <div class="card">
        <div class="card-header">
            <div class="row">
            <div class="col-xl-8 offset-xl-2 col-12">
                <div class="d-flex flex-column gap-2 mx-lg-8 text-center">
                <h2 class="mb-0">Berita Terbaru</h2>
                </div>
            </div>
            </div>
        </div>
        <div class="card-body p-0 p-lg-3">
            <div class="container p-1">
            <div class="row g-3 align-items-stretch">
                <!-- Left Main News -->
                <div class="col-12 col-md-4">
                    <a href="{{ route('user.berita_show', $berita->slug) }}" class="card card-equal shadow-sm">
                        <img src="{{ asset($berita->thumbnail) }}" class="news-thumbnail rounded" alt="Main News">
                        <div class="card-body d-flex flex-column">
                        <p class="fw-bold card-title title-2-line"> {{ $berita->title }} </p>
                        <p class="text-muted small mt-auto">Diposting oleh {{ $berita->operator }} - {{ $berita->publish_date }}</p>
                        </div>
                    </a>
                </div>
                <!-- Right News List -->
                <div class="col-12 col-md-8">
                    <div class="card card-equal shadow-sm p-1 p-lg-3 d-flex flex-column justify-content-between h-100">
                        <div class="d-flex flex-column gap-2">

                            @foreach($databerita as $datber)

                                <a  href="{{ route('user.berita_show', $datber->slug) }}" class="card p-2 d-flex flex-row gap-3 align-items-center shadow-sm">
                                    <img src="{{ asset($datber->thumbnail) }}" class="news-list-thumb rounded" alt="thumb">
                                    <div class="news-text-wrapper">
                                        <p class="mb-1 fw-semibold small title-1-line">{{ $datber->title }}</p>
                                        <small class="text-muted news-meta-wrap"> Diposting oleh {{ $datber->operator }} - {{ $datber->publish_date }}</small>
                                    </div>
                                </a>
                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="card-footer">
            <!-- Button -->
            <div class="text-center text-lg-end">
            <a href="{{route('user.berita')}}" class="btn btn-outline-dark"> Lihat Berita Lainnya <span class="ms-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right-circle-fill" viewBox="0 0 16 16">
                    <path d="M0 8a8 8 0 1 0 16 0A8 8 0 0 0 0 8zm5.904 2.803a.5.5 0 1 1-.707-.707L9.293 6H6.525a.5.5 0 1 1 0-1H10.5a.5.5 0 0 1 .5.5v3.975a.5.5 0 0 1-1 0V6.707l-4.096 4.096z"></path>
                </svg>
                </span>
            </a>
            </div>
        </div>
        </div>
    </div>
</section>
@endif

<div class="container my-5">
    <div class="mb-4">
        <h2 class="fw-bold">ESELON</h2>
        <div style="width: 50px; height: 3px; background-color: #007bff;"></div>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 g-mobile-2">

        @foreach($datapegawai as $pegawai)

        <div class="col">
            <div class="card border-0 shadow-sm profile-card">
                <img src="{{ asset($pegawai->foto) }}" class="card-img-top profile-img" alt="{{$pegawai->name}}">
                <div class="card-body">
                    <h6 class="mb-1">{{$pegawai->name}}</h6>
                    <p class="small">{{ $pegawai->jabatan }}</p>
                    <p class="small">{{ $pegawai->bidang }}</p>
                </div>
            </div>
        </div>

        @endforeach
    </div>
</div>
@endsection
