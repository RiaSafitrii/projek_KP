@extends('page.layouts.master')

@section('konten')


<section class="page-header py-5" style="background: linear-gradient(to right, #f8f9fa, #e9ecef); border-bottom: 1px solid #dee2e6;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{route('home')}}" class="text-decoration-none text-muted">Beranda</a></li>
                        <li class="breadcrumb-item active fw-medium" aria-current="page">Profile Pegawai</li>
                    </ol>
                </nav>
                <h1 class="fw-bold text-dark mb-0">Data Pegawai & Pejabat</h1>
                <p class="text-muted mb-0">Dinas Kesehatan Kota Bandar Lampung</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-soft-bone shadow-soft">
    <div class="container">
        <div class="mb-5">
            <h2 class="fw-bold position-relative d-inline-block">
                ESELON
                <span class="position-absolute start-0 bottom-0 w-50" style="height: 4px; background: #007bff; border-radius: 2px; margin-bottom: -10px;"></span>
            </h2>
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
</section>

@endsection
