@extends('page.layouts.master')

@section('konten')


<section class="py-5 bg-soft-pro border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('home')}}" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Profile</li>
            </ol>
        </nav>
        <h1 class="fw-bold text-dark display-6 mb-0">{{ $data->info_name }}</h1>
    </div>
</section>

<section class="py-5 bg-white">


    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12">
                @include('layouts.alert')
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    <div class="card-body p-4 p-md-5">
                        <div class="gambar-isi-ck-editor">
                            {!! $data->info_value !!}
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</section>


@endsection

