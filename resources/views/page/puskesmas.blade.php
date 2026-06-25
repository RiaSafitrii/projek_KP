@extends('page.layouts.master')

@section('konten')


<style>
    /* Font Modern (Poppins) */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f0f4f8; /* Abu-abu kebiruan sangat muda untuk background utama */
    }

    /* Judul UPT Puskesmas */
    .page-title {
        font-weight: 700;
        color: #00776b; /* Warna teal dari logo Puskesmas */
        text-align: center;
        margin-bottom: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .page-title .material-icons {
        font-size: 32px;
        color: #00776b;
    }

    /* Grid Kartu */
    .puskes-grid {
        gap: 1.5rem !important; /* Spasi yang lebih luas */
    }

    /* KARTU MODERN (The Magic) */
    .puskes-card {
        border: none;
        border-radius: 20px; /* Sudut lebih bulat */
        background: rgba(255, 255, 255, 0.9); /* Sedikit transparan untuk efek glass */
        backdrop-filter: blur(5px); /* Efek blur kaca */
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02); /* Bayangan sangat halus */
        padding: 1.5rem;
        height: 100%;
        cursor: pointer;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    /* Efek Hover */
    .puskes-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 119, 107, 0.1); /* Bayangan dengan aksen teal */
    }

    /* HEADER KARTU (Nama Puskesmas) */
    .card-header-mod {
        background: transparent;
        border: none;
        padding: 0;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .icon-faskes {
        width: 45px;
        height: 45px;
        background-color: #e0f2f1; /* Hijau teal muda */
        color: #00776b; /* Teal tua */
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .card-title {
        font-weight: 600;
        color: #2d3436;
        font-size: 1.15rem;
        margin: 0;
        flex: 1;
    }

    /* BADAN KARTU (Alamat) */
    .card-body-mod {
        padding: 0;
        flex: 1;
    }

    .card-address-item {
        color: #636e72;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 0.75rem;
        display: flex;
        gap: 8px;
        align-items: start;
    }

    .card-address-item .material-icons {
        font-size: 18px;
        color: #b2bec3; /* Abu-abu icon */
        margin-top: 3px;
    }

    /* LINK WEBSITE */
    .card-website {
        font-size: 0.9rem;
        color: #00776b;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s;
        margin-top: auto;
    }

    .card-website:hover {
        color: #00acc1; /* Teal lebih cerah saat hover */
    }
</style>


<section class="py-5 bg-soft-pro border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('home')}}" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Puskesmas</li>
            </ol>
        </nav>
    </div>
</section>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="page-title">
                <span class="material-icons">local_hospital</span>
                UPT Puskesmas
            </h2>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 px-3 px-md-0">

        @foreach($puskes as $pus)
            <div class="col">
                <a href="{{$pus->domain}}" class="puskes-card text-decoration-none" target="_blank">
                    <div class="card-header-mod">
                        <div class="icon-faskes">
                            <span class="material-icons">local_hospital</span>
                        </div>
                        <h5 class="card-title">{{$pus->name}}</h5>
                    </div>
                    <div class="card-body-mod">
                        <div class="card-address-item">
                            <span class="material-icons">place</span>
                            {{$pus->alamat}}
                        </div>
                    </div>
                    <div class="card-website mt-3">
                        <span class="material-icons">public</span> Website
                    </div>
                </a>
            </div>

        @endforeach



    </div>
</div>



@endsection
