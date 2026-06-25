@extends('page.layouts.master')

<!-- ini untuk masukin metanya untuk semua -->
@section('meta_description', $metaDescription)
@section('meta_title', $metaTitle)

@section('og_title', $metaTitle)
@section('og_description', $metaDescription)
@section('og_image', url($metaImage))

@section('twitter_title', $metaTitle)
@section('twitter_description', $metaDescription)
@section('twitter_image', url($metaImage))

@section('konten')


<section class="py-5 bg-soft-pro border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{route('home')}}" class="text-decoration-none text-muted">Beranda</a></li>
                <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Berita</li>
            </ol>
        </nav>
        <h1 class="fw-bold text-dark display-6 mb-0">{{ $news_detail->title }}</h1>
    </div>
</section>
<section class="py-5 bg-white">


    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                @include('layouts.alert')
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="share-container">
                        <img src="{{ asset($news_detail->thumbnail) }}" class="w-100 rounded-top-4" style="max-height: 500px; object-fit: cover;" alt="Thumbnail" />

                        <button class="share-trigger" onclick="toggleShareMenu()">
                            <span class="material-icons">share</span>
                        </button>

                        <div class="share-menu" id="shareMenu">
                            <a href="https://wa.me/?text={{ urlencode($news_detail->title . ' - ' . url()->current()) }}"
                            target="_blank" class="share-item" title="WhatsApp">
                                <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" width="20" alt="WA">
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                            target="_blank" class="share-item" title="Facebook">
                                <img src="https://cdn-icons-png.flaticon.com/512/5968/5968764.png" width="20" alt="FB">
                            </a>
                            <a href="#" class="share-item" onclick="copyToClipboard(event)" title="Salin Link">
                                <span class="material-icons">link</span>
                            </a>
                        </div>
                    </div>

                        <div id="copyNotice" class="copy-success small">Link berhasil disalin!</div>

                    <div class="card-body bg-light border-bottom d-flex justify-content-between align-items-center py-3 px-4">
                        <div class="small">
                            <span class="text-muted">Oleh:</span> <span class="fw-bold text-dark">{{ $news_detail->author ?? 'Admin' }}</span>
                            <span class="mx-2 text-muted">|</span>
                            <span class="text-muted">{{ $news_detail->publish_date }}</span>
                        </div>
                        <div>
                            <span class="reaction-pill me-2"><span class="material-icons">visibility</span>{{ $news_detail->views }}</span>
                            <span class="reaction-pill"><span class="material-icons">chat_bubble_outline</span> {{ $comment->count() }}</span>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <div class="gambar-isi-ck-editor">
                            {!! $news_detail->content !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($news_detail->hashtags->isNotEmpty())
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Hashtag Terkait</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($news_detail->hashtags as $hashtag)
                                <span class="badge bg-white text-primary border px-3 py-2 rounded-pill">#{{ $hashtag->hashtag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Berikan Komentar</h6>
                        <form action="{{ route('user.store_comment', encrypt($news_detail->id)) }}" method="POST">
                            @csrf
                            <input type="text" name="nama" class="form-control mb-2 bg-light border-0" placeholder="Nama Anda" required>
                            <textarea name="komentar" class="form-control mb-3 bg-light border-0" rows="3" placeholder="Tulis komentar..." required></textarea>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">Kirim Komentar</button>
                        </form>

                        <div class="comment-scroll-area mt-4" style="max-height: 400px; overflow-y: auto;">
                            @foreach($comment as $datacom)
                            <div class="d-flex mb-3 align-items-start">
                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width: 35px; height: 35px; background: #{{ substr(md5($datacom->name), 0, 6) }}; font-size: 0.8rem;">
                                    {{ strtoupper(substr($datacom->name, 0, 1)) }}
                                </div>
                                <div class="ms-2 bg-light p-3 rounded-4 w-100">
                                    <div class="d-flex justify-content-between">
                                        <small class="fw-bold">{{ $datacom->name }}</small>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $datacom->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 small text-secondary mt-1">{{ $datacom->comment }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($databerita->isNotEmpty())
<section class="py-5 bg-soft-pro"> <div class="container">
        <div class="d-flex align-items-center mb-5">
            <div class="me-3">
                <h3 class="fw-bold mb-0">Berita Rekomendasi</h3>
                <div style="width: 50px; height: 4px; background: #007bff; border-radius: 10px; margin-top: 5px;"></div>
            </div>
            <div class="flex-grow-1 border-bottom" style="opacity: 0.1;"></div>
        </div>

        <div class="row g-4">
            @foreach($databerita as $datber)
                <div class="col-12 col-md-4">
                    <div class="card rekomendasi-card shadow-sm h-100">
                        <a href="{{ route('user.berita_show', $datber->slug) }}" class="img-wrapper-rekomendasi">
                            <img src="{{ asset($datber->thumbnail) }}" alt="{{ $datber->title }}">
                            <div class="date-badge shadow-sm">
                                <span class="material-icons" style="font-size: 12px; vertical-align: middle;">calendar_today</span>
                                {{ $datber->publish_date }}
                            </div>
                        </a>

                        <div class="card-body p-4 d-flex flex-column">
                            <a href="{{ route('user.berita_show', $datber->slug) }}" class="news-title-link">
                                {{ $datber->title }}
                            </a>

                            <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center text-muted small">
                                    <span class="material-icons me-1" style="font-size: 16px;">person_outline</span>
                                    {{ $datber->operator }}
                                </div>
                                <a href="{{ route('user.berita_show', $datber->slug) }}" class="btn btn-link btn-sm text-decoration-none p-0 fw-bold">
                                    Baca <span class="material-icons" style="font-size: 14px; vertical-align: middle;">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<br><br>


<script>
    function toggleShareMenu() {
        const menu = document.getElementById('shareMenu');
        menu.classList.toggle('active');
    }

    // Menutup menu kalau klik di luar
    window.onclick = function(event) {
        if (!event.target.closest('.share-container')) {
            const menu = document.getElementById('shareMenu');
            if (menu.classList.contains('active')) {
                menu.classList.remove('active');
            }
        }
    }

    function copyToClipboard(e) {
        e.preventDefault();
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            const notice = document.getElementById('copyNotice');
            notice.style.display = 'block';
            setTimeout(() => {
                notice.style.display = 'none';
            }, 2000);
        });
    }
</script>


@endsection
