@extends('page.layouts.master')

@section('konten')


<div class="dkd-pattern-square"></div>
<section class="py-5 py-lg-8">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                        <li class="breadcrumb-item active title-1-line" aria-current="page">Daftar Berita</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="mb-xl-9">
    <div class="container">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-stretch custom-row" id="newsList">


                </div>
            </div>
            <div class="card-footer text-center">
            <button id="loadMoreBtn" class="btn btn-outline-primary">
                <i class="bi bi-arrow-down"></i>
                Lihat Lebih Banyak
            </button>
            </div>
        </div>
    </div>
</section>
<br>


<script>
let offset = 0;
const limit = 6;

function loadBerita() {
    fetch(`{{ route('user.berita') }}?offset=${offset}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.length === 0) {
            document.getElementById('loadMoreBtn').style.display = 'none';
            return;
        }

        offset += limit;

        data.forEach(item => {
            const newsItem = `

                    <div class="col-12 col-md-4 custom-col">
                        <div class="card card-equal shadow-sm">
                            <a href="/berita/detail/${item.slug}" class="dkd-image-hover-wrapper position-relative overflow-hidden">
                                <img src="/${item.thumbnail}" class="news-thumbnail rounded w-100" alt="${item.title}">

                            </a>
                            <div class="card-body d-flex flex-column">
                                <a href="/berita/detail/${item.slug}" class="mb-2 fw-bold title-2-line">
                                    ${item.title}
                                </a>
                                <p class="text-muted small mt-auto">Diposting oleh ${item.operator} - ${item.publish_date}</p>
                            </div>
                        </div>
                    </div>
            `;
            document.getElementById('newsList').insertAdjacentHTML('beforeend', newsItem);
        });
    })
    .catch(error => console.error('Gagal memuat berita:', error));
}

document.addEventListener('DOMContentLoaded', loadBerita);
document.getElementById('loadMoreBtn').addEventListener('click', loadBerita);
</script>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchBox = document.querySelector(".search-box");
        searchBox.addEventListener("keyup", function(event) {
            const value = event.target.value.toLowerCase();
            const customCols = document.querySelectorAll(".custom-row .custom-col");
            customCols.forEach(function(col) {
                const cardText = col.querySelector('.title-2-line').textContent.toLowerCase();
                if (cardText.indexOf(value) > -1) {
                    col.style.display = ""; // Show if matches search
                } else {
                    col.style.display = "none"; // Hide if it doesn't match search
                }
            });
        });
    });
</script>



@endsection
