@extends('layouts.master')

@section('konten')

<style>
.isi-halaman {
    padding: 20px;
}

.isi-halaman img {
    max-width: 100% !important;      /* Agar gambar tidak melampaui lebar kontainer */
    height: auto !important;         /* Menjaga proporsi gambar tetap sesuai */
    display: block !important;       /* Menghilangkan jarak bawah yang tidak diinginkan */
    margin: 0 auto !important;       /* Memusatkan gambar jika perlu */
    object-fit: contain !important;  /* Menjaga gambar dengan proporsi asli tanpa distorsi */
}
</style>


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Detail Konten</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Post</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('mstnews.index') }}">Kelola Konten</a></li>
                            <li class="breadcrumb-item active">Detail Konten</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <div class="text-center mb-3">
                                <h4>{{$news->title}}</h4>
                            </div>
                            <div class="mb-4">
                                <img src="{{ asset($news->thumbnail) }}" alt="" class="img-thumbnail mx-auto d-block">
                            </div>

                            <div class="text-center">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div>
                                            <h6 class="mb-2">Kategori</h6>
                                            <p class="text-muted font-size-15">{{ $news->category_name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mt-4 mt-sm-0">
                                            <h6 class="mb-2">Tanggal</h6>
                                            <p class="text-muted font-size-15">{{ $news->publish_date }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mt-4 mt-sm-0">
                                            <p class="text-muted mb-2">Diposting oleh</p>
                                            <h5 class="font-size-15">{{ $news->operator }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mt-4 mt-sm-0">
                                            <p class="text-muted mb-2">Penulis</p>
                                            <h5 class="font-size-15">{{ $news->penulis }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="mt-4">
                                <div class="text-muted font-size-14 isi-halaman">
                                    <p>
                                        {!! $news->content !!}
                                    </p>

                                </div>

                                <hr>
                                @if($news->hashtags->isNotEmpty())

                                    <div class="mt-4">
                                        <h5 class="font-size-16 mb-3">Tag:</h5>
                                        @foreach($news->hashtags as $hashtag)
                                            <span class="text-muted fw-bold me-1">#{{ $hashtag->hashtag }}</span>

                                        @endforeach
                                    </div>
                                    <hr>
                                @endif


                                <div class="mt-5">
                                    <h5 class="font-size-15"><i class="bx bx-message-dots text-muted align-middle me-1"></i> Komentar :</h5>

                                    @foreach($comment as $datacom)

                                        <div>

                                            <div class="d-flex py-3 border-top">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-xs">
                                                        <div class="avatar-title rounded-circle bg-light text-primary">
                                                            <i class="bx bxs-user"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="font-size-14 mb-1">{{ $datacom->name }}
                                                        <small class="text-muted float-end">Status:
                                                            <span class="badge {{ $datacom->is_approved ? 'bg-success' : 'bg-danger' }} text-white">
                                                                {{ $datacom->is_approved ? 'Disetujui' : 'Belum Disetujui' }}
                                                            </span>

                                                        </small>
                                                    </h5>
                                                    <h5 class="font-size-14 mb-1">
                                                        {{ Carbon\Carbon::parse($datacom->created_at)->translatedFormat('d F Y H:i') }}
                                                        <small class="text-muted float-end">
                                                            <form action="{{ route('mstnews.status_comment', encrypt($datacom->id)) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit" class="btn {{ $datacom->is_approved ? 'btn-warning' : 'btn-success' }} btn-sm waves-effect waves-light">
                                                                    {{ $datacom->is_approved ? 'Tidak Disetujui' : 'Disetujui' }}
                                                                </button>
                                                            </form>

                                                            <form action="{{ route('mstnews.delete_comment', encrypt($datacom->id)) }}" method="POST" style="display: inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm waves-effect waves-light">
                                                                    Hapus
                                                                </button>
                                                            </form>

                                                        </small>
                                                    </h5>
                                                    <p class="text-muted">{{ $datacom->comment }}</p>
                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                    @endforeach


                                </div>

                                <div class="mt-5">
                                    <h5 class="font-size-16 mb-3">Kirim Komentar:</h5>

                                    <form action="{{ route('mstnews.store_comment', encrypt($news->id)) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="commentmessage-input" class="form-label">Pesan</label>
                                            <textarea class="form-control" id="commentmessage-input" name="comment" placeholder="Komentar Anda..." rows="4" required></textarea>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary w-sm">Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end card body -->
                </div>
                <!-- end card -->
            </div>
        </div>

    </div>
</div>

@endsection
