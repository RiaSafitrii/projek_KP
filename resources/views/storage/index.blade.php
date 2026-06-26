@extends('layouts.master')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/dropzone/min/dropzone.min.css') }}">
<style>
    .dropzone {
        border: 2px dashed #ddd;
        padding: 20px;
        text-align: center;
        position: relative;
        cursor: pointer;
    }

    .dropzone.hover {
        border-color: #007bff;
    }

    .file-preview-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
        gap: 10px;
    }

    .file-preview-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
        width: 120px;
        position: relative;
    }

    .file-preview-item img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
    }

    .file-preview-item button {
        background: red;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
        position: absolute;
        top: 5px;
        right: 5px;
    }

    .file-preview-item button:hover {
        background: darkred;
    }

    /* Ikon PDF */
    .fas.fa-file-pdf {
        color: #e63946; /* Merah untuk PDF */
    }

    /* Ikon Word */
    .fas.fa-file-word {
        color: #1e3a8a; /* Biru untuk Word */
    }

    /* Ikon PowerPoint */
    .fas.fa-file-powerpoint {
        color: #f59e0b; /* Oranye untuk PowerPoint */
    }

    /* Ikon Excel */
    .fas.fa-file-excel {
        color: #10b981; /* Hijau untuk Excel */
    }


</style>
@endpush

@section('konten')

<style>
    /* Gaya untuk kontainer gambar */
    .image {
        text-align: center;
        display: block;
        width: 100%;
        height: 124px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
    }

    .image a {
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }

    .image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 3px;
    }


    /* Gaya untuk folder */
    .folder {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 124px;
    }

    .folder a {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .folder i {
        font-size: 72px;
        color: #f7ba12;
    }

    .folder i:hover {
        color: #f9c126;
    }


    /* pdf */
    .pdf {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 124px;
    }

    .pdf a {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: center;
    }


    /* Gaya untuk ikon */
    .pdf i {
        font-size: 72px;
        color: #e63946;
    }
    .pdf i:hover {
        color: #c6180b;
    }

    /* Word */
    .word {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 124px;
    }

    .word a {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: center;
    }


    /* Gaya untuk ikon */
    .word i {
        font-size: 72px;
        color: #1e3a8a;
    }
    .word i:hover {
        color: #266dd0;
    }


    /* Excel */
    .excel {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 124px;
    }

    .excel a {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: center;
    }


    /* Gaya untuk ikon */
    .excel i {
        font-size: 72px;
        color: #10b981;
    }
    .excel i:hover {
        color: #108f4e;
    }

    /* powerpoint */
    .powerpoint {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        height: 124px;
    }

    .powerpoint a {
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: center;
    }


    /* Gaya untuk ikon */
    .powerpoint i {
        font-size: 72px;
        color: #f59e0b;
    }
    .powerpoint i:hover {
        color: #f1622d;
    }


    .truncated-text {
        display: -webkit-box;
        -webkit-line-clamp: 2; /* Membatasi teks menjadi 2 baris */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis; /* Menambahkan "..." pada teks yang terpotong */
        font-size: 10px;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        /* Image */
        .image {
            height: 101px;
        }
        .folder {
            height: 101px
        }
        .word {
            height: 101px
        }
        .excel {
            height: 101px
        }

    }
</style>
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Penyimpanan Data</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Post</a></li>
                            <li class="breadcrumb-item active">Kelola Penyimpanan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="email-leftbar card">
                    <button type="button" class="btn btn-info btn-block waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#add-new">
                        Tambah Baru
                    </button>

                    {{-- Modal Tambah Baru --}}
                    <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-top modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Tambah Baru</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <form action="{{ route('storage.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div id="dropzone-container" class="dropzone">
                                                <input type="file" accept="image/png, image/jpeg, image/jpg, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" id="realFileInput" name="data_file_input[]" multiple hidden>
                                                <div id="input-dropzone-manual" class="dz-message needsclick">
                                                    <div class="mb-3">
                                                        <i class="display-4 text-muted bx bx-cloud-upload"></i>
                                                    </div>
                                                    <h5>Tarik file ke sini atau klik untuk unggah.</h5>
                                                </div>
                                                <div id="file-preview" class="file-preview-container"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-checkbox-marked-circle-plus-outline label-icon"></i>Tambah</button>
                                    </div>
                                </form>
                                <script>
                                    document.getElementById('formadd').addEventListener('submit', function(event) {
                                        if (!this.checkValidity()) {
                                            event.preventDefault();
                                            return false;
                                        }
                                        var submitButton = this.querySelector('button[name="sb"]');
                                        submitButton.disabled = true;
                                        submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Mohon Tunggu...';
                                        return true;
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="email-rightbar">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @foreach($filestorage as $storage)
                                    @if($storage['jenis'] === 'file')
                                        @php
                                            $extension = pathinfo($storage->name, PATHINFO_EXTENSION);
                                        @endphp

                                        <div class="col-xl-2 col-6">
                                            <div class="card">
                                                <div class="
                                                    @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                                        image
                                                    @elseif($extension === 'pdf')
                                                        pdf
                                                    @elseif(in_array($extension, ['doc', 'docx']))
                                                        word
                                                    @elseif(in_array($extension, ['xls', 'xlsx']))
                                                        excel
                                                    @elseif(in_array($extension, ['ppt', 'pptx']))
                                                        powerpoint
                                                    @endif
                                                ">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#edit-data{{ $storage->id }}">
                                                        @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                                            <img src="{{ asset($storage->path_file) }}" alt="Gambar" class="img-fluid">
                                                        @elseif($extension === 'pdf')
                                                            <i class="fas fa-file-pdf"></i>
                                                        @elseif(in_array($extension, ['doc', 'docx']))
                                                            <i class="fas fa-file-word"></i>
                                                        @elseif(in_array($extension, ['xls', 'xlsx']))
                                                            <i class="fas fa-file-excel"></i>
                                                        @elseif(in_array($extension, ['ppt', 'pptx']))
                                                            <i class="fas fa-file-powerpoint"></i>
                                                        @endif
                                                        <span class="ms-1 text-muted font-size-10 truncated-text">{{ $storage->name }}</span>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="left-align truncate-text">
                                                {{-- Modal Detail --}}
                                                <div class="modal fade" id="edit-data{{ $storage->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-top" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="staticBackdropLabel">Detail File</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                                                        <img src="{{ asset($storage->path_file) }}" class="img-fluid" style="max-height: 300px; object-fit: contain;">
                                                                        <span><i class="fas fa-file-image mt-2"></i> : {{ $storage->name }}</span>
                                                                    @elseif($extension === 'pdf')
                                                                        <span><i class="fas fa-file-pdf"></i> : {{ $storage->name }}</span>
                                                                    @elseif(in_array($extension, ['doc', 'docx']))
                                                                        <span><i class="fas fa-file-word"></i> : {{ $storage->name }}</span>
                                                                    @elseif(in_array($extension, ['xls', 'xlsx']))
                                                                        <span><i class="fas fa-file-excel"></i> : {{ $storage->name }}</span>
                                                                    @elseif(in_array($extension, ['ppt', 'pptx']))
                                                                        <span><i class="fas fa-file-powerpoint"></i> : {{ $storage->name }}</span>
                                                                    @endif

                                                                    <span><strong>Pengunggah</strong> : {{ $storage->writer }}</span>
                                                                    <span><strong>Tanggal</strong> : {{ Carbon\Carbon::parse($storage->created_at)->translatedFormat('d F Y H:i') }}</span>

                                                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                                                        <a class="btn btn-primary waves-effect waves-light" href="{{ asset($storage->path_file) }}" download="{{ $storage->name }}">
                                                                            <i class="bx bxs-download font-size-16 align-middle me-2"></i> Unduh
                                                                        </a>
                                                                        <button type="button" class="btn btn-light waves-effect" id="copyButton{{ $storage->id }}">
                                                                            <i class="bx bx-copy font-size-16 align-middle me-2"></i> Salin Tautan
                                                                        </button>
                                                                        <script>
                                                                            document.getElementById('copyButton{{ $storage->id }}').addEventListener('click', function() {
                                                                                var imageUrl = "{{ asset($storage->path_file) }}";
                                                                                if (!imageUrl.startsWith('http')) {
                                                                                    imageUrl = window.location.origin + imageUrl;
                                                                                }
                                                                                navigator.clipboard.writeText(imageUrl)
                                                                                    .then(function() {
                                                                                        alert('Tautan file berhasil disalin!');
                                                                                    })
                                                                                    .catch(function(error) {
                                                                                        alert('Gagal menyalin tautan: ' + error);
                                                                                    });
                                                                            });
                                                                        </script>

                                                                        <form action="{{ route('storage.delete', encrypt($storage->id)) }}" method="POST">
                                                                            @method('delete')
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-danger waves-effect">
                                                                                <i class="mdi mdi-trash-can font-size-16 align-middle me-2"></i> Hapus
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


@endsection
@push('scripts')
<script src="{{ asset('assets/libs/dropzone/min/dropzone.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropzoneContainer = document.getElementById('input-dropzone-manual');
        const realFileInput = document.getElementById('realFileInput');
        const filePreview = document.getElementById('file-preview');
        let isFileUploaded = false; // Track apakah ada file yang ter-upload
        let filesToRemove = []; // Menyimpan id gambar yang akan dihapus

        // Klik pada Dropzone untuk membuka file chooser
        dropzoneContainer.addEventListener('click', function () {
            realFileInput.click();
        });

        // Drag-and-Drop
        dropzoneContainer.addEventListener('dragover', function (e) {
            e.preventDefault();
            dropzoneContainer.classList.add('hover');
        });

        dropzoneContainer.addEventListener('dragleave', function () {
            dropzoneContainer.classList.remove('hover');
        });

        dropzoneContainer.addEventListener('drop', function (e) {
            e.preventDefault();
            dropzoneContainer.classList.remove('hover');
            const files = e.dataTransfer.files;
            handleFiles(files);
        });

        // Saat file di-upload via input
        realFileInput.addEventListener('change', function () {
            handleFiles(realFileInput.files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const dataTransfer = new DataTransfer();

                // Masukkan file ke input yang sesuai
                for (const file of files) {
                    if (isValidFile(file)) {
                        addFilePreview(file);

                        // Tambahkan file ke DataTransfer untuk input file
                        dataTransfer.items.add(file);
                    } else {
                        alert('Hanya file PNG, JPG, JPEG, PDF, DOCX, PPTX, XLSX yang diperbolehkan!');
                    }
                }

                // Memperbarui input file dengan file yang sudah ditambahkan
                realFileInput.files = dataTransfer.files;
                isFileUploaded = true; // Set flag file ter-upload
            }
        }


        // Validasi tipe file (gambar dan dokumen)
        function isValidFile(file) {
            const validTypes = [
                'image/png', 'image/jpeg', 'image/jpg', // Gambar
                'application/pdf', // PDF
                'application/msword', // Word (doc)
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // Word (docx)
                'application/vnd.ms-powerpoint', // PowerPoint (ppt)
                'application/vnd.openxmlformats-officedocument.presentationml.presentation', // PowerPoint (pptx)
                'application/vnd.ms-excel', // Excel (xls)
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' // Excel (xlsx)
            ];
            return validTypes.includes(file.type);
        }

        function addFilePreview(file) {
            const preview = document.createElement('div');
            preview.classList.add('file-preview-item');

            // Preview gambar (jika file gambar)
            if (file.type.startsWith('image')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                preview.appendChild(img);
            } else {
                // Untuk file selain gambar (PDF, Word, dll.), tampilkan nama dan ikon
                const fileName = document.createElement('span');
                fileName.textContent = file.name; // Tampilkan nama file

                // Menambahkan ikon sesuai jenis file (misalnya PDF, Word, dll.)
                const icon = document.createElement('span');
                if (file.type === 'application/pdf') {
                    icon.classList.add('fas', 'fa-file-pdf'); // Ikon Font Awesome PDF
                } else if (file.type === 'application/msword' || file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                    icon.classList.add('fas', 'fa-file-word'); // Ikon Font Awesome Word
                } else if (file.type === 'application/vnd.ms-powerpoint' || file.type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation') {
                    icon.classList.add('fas', 'fa-file-powerpoint'); // Ikon Font Awesome PowerPoint
                } else if (file.type === 'application/vnd.ms-excel' || file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                    icon.classList.add('fas', 'fa-file-excel'); // Ikon Font Awesome Excel
                }

                preview.appendChild(icon);
                preview.appendChild(fileName);
            }

            // Tombol hapus
            const removeButton = document.createElement('button');
            removeButton.textContent = 'X';
            removeButton.addEventListener('click', function () {
                preview.remove();
                removeFile(file);
            });
            preview.appendChild(removeButton);

            filePreview.appendChild(preview);
        }



        function removeFile(fileToRemove) {
            const dataTransfer = new DataTransfer();
            Array.from(realFileInput.files).forEach(file => {
                if (file !== fileToRemove) {
                    dataTransfer.items.add(file);
                }
            });
            realFileInput.files = dataTransfer.files;

            // Jika tidak ada file yang tersisa, set flag isFileUploaded ke false
            if (realFileInput.files.length === 0) {
                isFileUploaded = false;
            }
        }

        // Handle penghapusan gambar yang sudah ada di server
        filePreview.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-image')) {
                const imageId = e.target.dataset.imageId;
                filesToRemove.push(imageId); // Tambahkan ID gambar yang dihapus ke array
                e.target.closest('.file-preview-item').remove(); // Hapus elemen gambar
                console.log('Removed image ID:', imageId); // Debugging
            }
        });

        // Saat form disubmit, sertakan gambar yang dihapus
        const form = document.querySelector('#formupdate'); // Menggunakan ID 'formupdate'
        form.addEventListener('submit', function (event) {
            console.log('Form is being submitted'); // Debugging

            // Menambahkan id gambar yang akan dihapus ke input tersembunyi
            if (filesToRemove.length > 0) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'files_to_remove';
                input.value = filesToRemove.join(','); // Gambar yang akan dihapus
                form.appendChild(input);
                console.log('Files to remove:', filesToRemove); // Debugging
            }

            // Cegah submit form terlebih dahulu, untuk memastikan input sudah ditambahkan
            event.preventDefault();

            // Debug: Periksa apakah input sudah benar ditambahkan
            console.log('Hidden input:', form.querySelector('input[name="files_to_remove"]'));

            // Setelah semuanya diperiksa, submit form secara manual
            form.submit();
        });

    });
</script>

@endpush
