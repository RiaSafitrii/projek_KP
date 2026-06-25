@extends('layouts.master')
@push('styles')

@endpush
@section('konten')

<div class="page-content">
    <div class="container-fluid">

        <!-- mulai judul halaman -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master Post</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('mstnews.index') }}">Kelola Konten</a></li>
                        <li class="breadcrumb-item active">
                            <?= ($case == 'add') ? 'Form Tambah Konten' : 'Form Edit Konten' ?>
                        </li>
                    </ol>
                    <a id="backButton" type="button" href="{{ route('mstnews.index') }}"
                        class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left-circle label-icon"></i>
                        Kembali
                    </a>

                </div>
            </div>
        </div>
        <!-- akhir judul halaman -->

        @include('layouts.alert')

        <div class="row">
            @if($case == 'add')
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <h4 class="card-title"> <span class="text-muted">Form Tambah Konten</span></h4>
                                <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#generate-konten">
                                <i class="mdi mdi-brain label-icon"></i> Generate Konten </button>
                                <!-- Modal Generate Konten -->
                                <div class="modal fade" id="generate-konten" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Pembuatan Berita Otomatis</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Info Berita -->
                                            <div class="card shadow-sm mb-4">
                                            <div class="card-body">
                                                {{-- <h5 class="card-title">Info Berita</h5> --}}
                                                <div class="mb-3">
                                                <label for="kategori" class="form-label">Kategori Berita</label>
                                                <select class="form-select" id="kategori" required>
                                                    <option selected disabled value="">-- Pilih Kategori --</option>
                                                    <option>Berita Pencapaian dan Prestasi</option>
                                                    <option>Berita Program</option>
                                                    <option>Berita Kegiatan</option>
                                                    <option>Berita Mendalam</option>
                                                </select>
                                                </div>
                                                <div class="mb-3">
                                                <label for="gaya" class="form-label">Gaya Lead</label>
                                                <select class="form-select" id="gaya" required>
                                                    <option selected disabled value="">-- Pilih Gaya Lead --</option>
                                                    <option>Lead Ringkasan (Summary Lead)</option>
                                                    <option>Lead Langsung (Straight News Lead)</option>
                                                    <option>Lead Kutipan (Quotation Lead)</option>
                                                    <option>Lead Pertanyaan (Question Lead)</option>
                                                    <option>Lead Deskriptif (Desscripptive Lead)</option>
                                                    <option>Lead Naratif (Narrative Lead)</option>
                                                </select>
                                                </div>
                                            </div>
                                            </div>
                                            <!-- Materi Berita -->
                                            <div class="card shadow-sm mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Materi Berita (5W + 1H)</h5>
                                                <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">What (Apa Yang terjadi)</label>
                                                    <input type="text" class="form-control" id="what" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Who (Siapa Saja)</label>
                                                    <input type="text" class="form-control" id="who" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">When (Kapan Waktu)</label>
                                                    <input type="text" class="form-control" id="when" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Where (Dimana Kegiatan)</label>
                                                    <input type="text" class="form-control" id="where" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Why (Mengapa ini Penting)</label>
                                                    <input type="text" class="form-control" id="why" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">How (Bagaimana Proses)</label>
                                                    <input type="text" class="form-control" id="how" required>
                                                </div>
                                                </div>
                                            </div>
                                            </div>
                                            <!-- Narasumber -->
                                            <div class="card shadow-sm mb-4">
                                            <div class="card-body">
                                                <h5 class="card-title">Jumlah Narasumber</h5>
                                                <div class="mb-3">
                                                <input type="number" class="form-control" id="jumlahNarsum" placeholder="Masukkan jumlah narasumber">
                                                </div>
                                                <div id="formNarsum"></div>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                            <button type="button" class="btn btn-success" id="submitBtn" onclick="generateLink()" disabled>
                                            <i class="mdi mdi-brain label-icon"></i> Generate </button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    const requiredFieldIds = ['kategori', 'gaya', 'what', 'who', 'when', 'where', 'why', 'how'];
                                    const submitBtn = document.getElementById('submitBtn');

                                    // Pantau semua field
                                    requiredFieldIds.forEach(id => {
                                        document.getElementById(id).addEventListener('input', checkFormValidity);
                                    });

                                    function checkFormValidity() {
                                        let allFilled = requiredFieldIds.every(id => {
                                            const el = document.getElementById(id);
                                            return el && el.value.trim() !== '';
                                        });
                                        submitBtn.disabled = !allFilled;
                                    }

                                    function createNarsumForms(jumlah) {
                                        const container = document.getElementById('formNarsum');
                                        container.innerHTML = '';
                                        for (let i = 1; i <= jumlah; i++) {
                                        container.innerHTML += `

                                                                    <div class="border rounded p-3 mb-3">
                                                                        <h6>Narasumber ${i}</h6>
                                                                        <input type="text" class="form-control mb-2" placeholder="Nama" id="narsumNama${i}">
                                                                            <input type="text" class="form-control mb-2" placeholder="Jabatan" id="narsumJabatan${i}">
                                                                                <textarea class="form-control" placeholder="Kutipan" id="narsumKutipan${i}"></textarea>
                                                                            </div>
                                                                        `;
                                        }
                                    }
                                    document.getElementById('jumlahNarsum').addEventListener('input', function() {
                                        const jumlah = parseInt(this.value);
                                        if (!isNaN(jumlah) && jumlah > 0) {
                                        createNarsumForms(jumlah);
                                        }
                                    });

                                    function encode(val) {
                                        return encodeURIComponent(val || '');
                                    }




                                    function generateLink() {
                                        const kategori = document.getElementById('kategori').value;
                                        const gaya = document.getElementById('gaya').value;
                                        const what = document.getElementById('what').value;
                                        const who = document.getElementById('who').value;
                                        const when = document.getElementById('when').value;
                                        const where = document.getElementById('where').value;
                                        const why = document.getElementById('why').value;
                                        const how = document.getElementById('how').value;

                                        let prompt = '';

                                        // Kalau gaya naratif, pakai prompt khusus
                                        if (gaya.includes('Naratif') || gaya.includes('Deskriptif')) {
                                            prompt = `Saya adalah seorang jurnalis profesional media online. Buatkan narasi berita lengkap dan utuh tanpa pembagian bagian.
                                            Gunakan gaya **naratif yang profesional dan netral**, tanpa kalimat berlebihan seperti "suasana penuh haru" atau ungkapan subjektif lainnya.

                                            Awali berita dengan format lokasi dan tanggal seperti "nama lokasi, 22 Juni 2025 —", langsung masuk ke peristiwa utamanya.

                                            Pastikan paragraf pertama mencerminkan inti peristiwa sesuai kategori berita ${kategori}. Gunakan prinsip 5W + 1H, dengan penekanan pada Why dan How.
                                            Narasi harus mengalir alami seperti artikel berita media profesional nasional (hindari gaya berlebihan seperti tulisan AI atau novel).

                                            Berikut informasi beritanya:
                                            What: ${what}
                                            Who: ${who}
                                            When: ${when}
                                            Where: ${where}
                                            Why: ${why}
                                            How: ${how}

                                            Buatkan juga judul berita otomatis yang menarik dan relevan. Sertakan 10 hashtag populer dalam format list.`;
                                        } else {
                                            // Default untuk gaya lainnya
                                            prompt = `Saya adalah seorang jurnalis profesional media online. Tugas saya adalah menulis berita resmi untuk instansi pemerintahan.

                                            Tolong buatkan:
                                            1. **Judul berita yang informatif dan menarik** sesuai standar jurnalistik Indonesia.
                                            2. **Narasi berita lengkap** dalam satu kesatuan paragraf (tidak terpisah-pisah), tanpa pembagian bagian, tanpa label, dan tidak terlihat seperti tulisan AI.
                                            3. Gunakan gaya penulisan ${gaya}, dengan tone netral, profesional, dan tidak berlebihan. Hindari frasa seperti "penuh haru", "suasana menyelimuti", atau gaya yang terlalu dramatis.
                                            4. Awali paragraf pertama dengan format lokasi dan tanggal seperti: "Purworejo, 22 Juni 2025 —".
                                            5. Narasi harus mengalir alami seperti artikel media nasional. Gunakan prinsip 5W + 1H, dengan penekanan pada Why dan How agar kontekstual.
                                            6. Tambahkan 10 hashtag populer dan relevan dalam format list seperti:
                                            #ContohTag1
                                            #ContohTag2

                                            Berikut adalah informasi dasar untuk ditulis:
                                            What: ${what}
                                            Who: ${who}
                                            When: ${when}
                                            Where: ${where}
                                            Why: ${why}
                                            How: ${how}`;
                                        }

                                        // Tambahkan kutipan narasumber jika ada
                                        const jumlah = parseInt(document.getElementById('jumlahNarsum').value);
                                        for (let i = 1; i <= jumlah; i++) {
                                            const nama = document.getElementById(`narsumNama${i}`).value;
                                            const jabatan = document.getElementById(`narsumJabatan${i}`).value;
                                            const kutipan = document.getElementById(`narsumKutipan${i}`).value;
                                            prompt += `\n${nama} (${jabatan}) menyatakan: "${kutipan}"`;
                                        }

                                        const finalLink = `https://chatgpt.com/?prompt=${encodeURIComponent(prompt)}`;
                                        window.open(finalLink, '_blank');
                                    }


                                </script>


                            </div>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('mstnews.store') }}" id="formaddnews" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row ">
                                    <div class="col-lg-4 mb-3">
                                        <label for="id_category" class="form-label">Kategori</label><label style="color: darkred">*</label>
                                        <select class="form-control" name="id_category" id="id_category" required>
                                            <option value="" disabled selected>-- Pilih Kategori --</option>
                                            @foreach($categories as $cate)
                                                <option value="{{$cate->id}}">{{$cate->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="author_id" class="form-label">Penulis</label><label style="color: darkred">*</label>
                                        <select class="form-control" name="author_id" id="author_id">
                                            <option value="" disabled selected>-- Pilih Penulis --</option>
                                            @foreach($penulis as $pen)
                                                <option value="{{$pen->id}}">{{$pen->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="publish_date">Tanggal Terbit</label><label style="color: darkred">*</label>
                                            <input type="datetime-local" class="form-control" id="publish_date" name="publish_date" placeholder="Input Tanggal Terbit..." required>
                                        </div>
                                    </div>

                                    <div class="col-lg-4" id="finaldate_group" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label" for="finaldate_of_announcement">Tanggal Akhir</label><label style="color: darkred">*</label>
                                            <input type="datetime-local" class="form-control" id="finaldate_of_announcement" name="finaldate_of_announcement" placeholder="Input Tanggal Akhir Pengumuman...">
                                        </div>
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const categorySelect = document.getElementById('id_category');
                                            const finalDateGroup = document.getElementById('finaldate_group');

                                            categorySelect.addEventListener('change', function () {
                                                // Ambil teks kategori yang dipilih
                                                const selectedText = categorySelect.options[categorySelect.selectedIndex].text.trim();

                                                if (selectedText.toLowerCase() === 'pengumuman') {
                                                    finalDateGroup.style.display = 'block';
                                                    document.getElementById('finaldate_of_announcement').required = true;
                                                } else {
                                                    finalDateGroup.style.display = 'none';
                                                    document.getElementById('finaldate_of_announcement').required = false;
                                                    document.getElementById('finaldate_of_announcement').value = '';
                                                }
                                            });
                                        });

                                    </script>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="title">Judul</label><label style="color: darkred">*</label>
                                            <input type="text" class="form-control" id="title" name="title" placeholder="Input Judul..." required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="thumbnail">Thumbnail</label><label style="color: darkred">(Resolusi Disarankan 800x350px)*</label>
                                        <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" id="thumbnail" name="thumbnail">
                                        <img id="previewImage" src="#" class="img-thumbnail mt-2" alt="Preview Image" width="400" style="display: none;">
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Konten</label><label style="color: darkred">*</label>
                                        <textarea id="content" name="content"></textarea>
                                    </div>

                                    <script>

                                        // Fungsi untuk menampilkan pratinjau gambar
                                        function previewImage(event) {
                                            var input = event.target;
                                            var reader = new FileReader();
                                            reader.onload = function() {
                                                var imgElement = document.getElementById("previewImage");
                                                imgElement.src = reader.result;
                                                imgElement.style.display = "block"; // Menampilkan pratinjau gambar
                                            };
                                            reader.readAsDataURL(input.files[0]);
                                        }

                                        // Menambahkan event listener untuk input file
                                        document.getElementById("thumbnail").addEventListener("change", previewImage);

                                        // Inisialisasi CKEditor
                                        CKEDITOR.replace('content', {
                                            versionCheck: false,
                                            extraPlugins: 'autogrow',
                                            autoGrow_onStartup: true,
                                            autoGrow_minHeight: 300,
                                            autoGrow_maxHeight: 700,
                                            autoGrow_bottomSpace: 50,
                                            toolbar: [
                                                { name: 'clipboard', groups: ['clipboard', 'undo'], items: ['Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo'] },
                                                { name: 'editing', groups: ['find', 'selection', 'spellchecker'], items: ['Find', 'Replace'] },
                                                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                                                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-'] },
                                                { name: 'links', items: ['Link', 'Unlink'] },
                                                { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar' ] },
                                                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                                                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                                                { name: 'others', items: ['-'] },
                                            ]
                                        });
                                    </script>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="hashtag">Tags</label>
                                            <input type="text" class="form-control" id="hashtag" name="hashtag" placeholder="Input Hashtags...">
                                        </div>
                                    </div>
                                </div>


                                <button class="btn btn-primary float-end me-4 mt-2" id="sb-addnews" type="submit">Kirim</button>
                            </form>

                            <script>
                                $(document).ready(function() {
                                    $('#formaddnews').submit(function(e) {
                                        if (!$('#formaddnews').valid()){
                                            e.preventDefault();
                                        } else {
                                            $('#sb-addnews').attr("disabled", "disabled");
                                            $('#sb-addnews').html('<i class="mdi mdi-reload label-icon"></i>Tolong Tunggu...');
                                        }
                                    });
                                });
                            </script>

                        </div>
                    </div>
                    <!-- akhir kartu -->
                </div> <!-- akhir kolom -->
            @elseif($case == 'edit')
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-start gap-3 flex">
                                <h4 class="card-title"> <span class="text-muted">Form Edit Konten</span> </h4>
                            </div>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('mstnews.update', encrypt($news->id) ) }}" id="formupdate" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row ">
                                    <div class="col-lg-4 mb-3">
                                        <label for="id_category" class="form-label font-size-13 text-muted">Kategori</label><label style="color: darkred">*</label>
                                        <select class="form-control" name="id_category" id="id_category" required>
                                            <option value="" disabled selected>-- Pilih Kategori --</option>
                                            @foreach($categories as $cate)
                                                <option value="{{$cate->id}}" {{($news->category_id == $cate->id) ? 'selected' : ''}}>{{$cate->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 mb-3">
                                        <label for="author_id" class="form-label">Penulis</label><label style="color: darkred">*</label>
                                        <select class="form-control" name="author_id" id="author_id">
                                            <option value="" disabled selected>-- Pilih Penulis --</option>
                                            @foreach($penulis as $pen)
                                                <option value="{{$pen->id}}" {{($news->author_id == $pen->id) ? 'selected' : ''}}>{{$pen->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label class="form-label" for="publish_date">Tanggal Terbit</label><label style="color: darkred">*</label>
                                            <input type="datetime-local" class="form-control" id="publish_date" name="publish_date" value="{{$news->publish_date}}" placeholder="Input Tanggal Terbit..." required>
                                        </div>
                                    </div>

                                    <div class="col-lg-4" id="finaldate_group" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label" for="finaldate_of_announcement">Tanggal Akhir</label><label style="color: darkred">*</label>
                                            <input type="datetime-local" class="form-control" id="finaldate_of_announcement" name="finaldate_of_announcement" value="{{ $news->finaldate_of_announcement }}" placeholder="Input Tanggal Akhir Pengumuman...">
                                        </div>
                                    </div>


                                    <script>
                                        document.addEventListener('DOMContentLoaded', function () {
                                            const categorySelect = document.getElementById('id_category');
                                            const finalDateGroup = document.getElementById('finaldate_group');
                                            const finalDateInput = document.getElementById('finaldate_of_announcement');

                                            function toggleFinalDate() {
                                                const selectedText = categorySelect.options[categorySelect.selectedIndex].text.trim().toLowerCase();
                                                if (selectedText === 'pengumuman') {
                                                    finalDateGroup.style.display = 'block';
                                                    finalDateInput.required = true;
                                                } else {
                                                    finalDateGroup.style.display = 'none';
                                                    finalDateInput.required = false;
                                                    finalDateInput.value = '';
                                                }
                                            }

                                            // Jalankan saat halaman load
                                            toggleFinalDate();

                                            // Jalankan saat kategori berubah
                                            categorySelect.addEventListener('change', toggleFinalDate);
                                        });
                                        </script>



                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="title">Judul</label><label style="color: darkred">*</label>
                                            <input type="text" class="form-control" id="title" name="title" value="{{$news->title}}" placeholder="Input Judul..." required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="thumbnail">Thumbnail</label><label style="color: darkred">*</label>
                                        <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" id="thumbnail" name="thumbnail">
                                        <img id="previewImage" src="{{asset($news->thumbnail)}}" class="img-thumbnail mt-2" alt="Preview Image" width="400" style="{{ ($news->thumbnail == null) ? 'display: none;' : '' }}">

                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Konten</label><label style="color: darkred">*</label>
                                        <textarea id="content" name="content">{{ $news->content }}</textarea>
                                    </div>

                                    <script>
                                        // Fungsi untuk menampilkan pratinjau gambar
                                        function previewImage(event) {
                                            var input = event.target;
                                            var reader = new FileReader();
                                            reader.onload = function() {
                                                var imgElement = document.getElementById("previewImage");
                                                imgElement.src = reader.result;
                                                imgElement.style.display = "block"; // Menampilkan pratinjau gambar
                                            };
                                            reader.readAsDataURL(input.files[0]);
                                        }

                                        // Menambahkan event listener untuk input file
                                        document.getElementById("thumbnail").addEventListener("change", previewImage);

                                        // Inisialisasi CKEditor
                                        CKEDITOR.replace('content', {
                                            versionCheck: false,
                                            extraPlugins: 'autogrow',
                                            autoGrow_onStartup: true,
                                            autoGrow_minHeight: 300,
                                            autoGrow_maxHeight: 700,
                                            autoGrow_bottomSpace: 50,
                                            toolbar: [
                                                { name: 'clipboard', groups: ['clipboard', 'undo'], items: ['Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo'] },
                                                { name: 'editing', groups: ['find', 'selection', 'spellchecker'], items: ['Find', 'Replace'] },
                                                { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                                                { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-'] },
                                                { name: 'links', items: ['Link', 'Unlink'] },
                                                { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar' ] },
                                                { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                                                { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                                                { name: 'others', items: ['-'] },
                                            ]
                                        });

                                    </script>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label" for="hashtag">Tags</label>
                                            <input type="text" class="form-control" id="hashtag" name="hashtag" value="{{$hashtags}}" placeholder="Input Hashtags...">
                                        </div>
                                    </div>

                                </div>


                                <button class="btn btn-primary float-end me-4 mt-2" id="sb-update" type="submit">Perbarui</button>
                            </form>
                            <script>
                                $(document).ready(function() {
                                    $('#formupdate').submit(function(e) {
                                        if (!$('#formupdate').valid()){
                                            e.preventDefault();
                                        } else {
                                            $('#sb-update').attr("disabled", "disabled");
                                            $('#sb-update').html('<i class="mdi mdi-reload label-icon"></i>Tolong Tunggu...');
                                        }
                                    });
                                });
                            </script>

                        </div>
                    </div>
                    <!-- akhir kartu -->
                </div> <!-- akhir kolom -->
            @endif

        </div><!-- akhir baris -->
    </div>
    <!-- container-fluid -->
</div>

@endsection
@push('scripts')

@endpush
