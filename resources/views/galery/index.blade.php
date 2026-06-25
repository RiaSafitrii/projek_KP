@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Galeri</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Post</a></li>
                            <li class="breadcrumb-item active">Galeri</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-tray-plus label-icon"></i> Tambah</button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Tambah</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('mstgalery.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Galery</label><label style="color: darkred">*</label>
                                                    <input class="form-control" id="name" name="name" type="text" value="" placeholder="Masukan Nama Galery.." required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jenis" class="form-label">Jenis</label><label style="color: darkred">*</label>
                                                    <select class="form-select" name="jenis" id="jenis" onchange="toggleJeniscreate()">
                                                        <option value="" disabled selected>-- Pilih Jenis --</option>
                                                        <option value="foto">Foto</option>
                                                        <option value="video">Video</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3" id="tempat-foto" style="display: none;">
                                                    <label class="form-label" for="thumbnail">Foto</label><label style="color: darkred">*</label>
                                                    <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" id="foto" name="foto">
                                                    <div class="d-flex justify-content-center mt-2">
                                                        <img id="previewImage" src="#" class="img-thumbnail" alt="Preview Image" style="display: none; width: 300px; height: 180px; position: relative;">
                                                    </div>
                                                    <script>
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
                                                        document.getElementById("foto").addEventListener("change", previewImage);
                                                    </script>
                                                </div>

                                                <div class="mb-3" id="tempat-video" style="display: none;">
                                                    <label class="form-label">Url Video Youtube</label><label style="color: darkred">*</label>
                                                    <input class="form-control" id="video" name="video" type="url" placeholder="Masukan Url Video Youtube..">
                                                </div>

                                                <script>
                                                    function toggleJeniscreate() {
                                                        var jenis = document.getElementById("jenis").value;
                                                        var tempatFoto = document.getElementById("tempat-foto");
                                                        var tempatVideo = document.getElementById("tempat-video");

                                                        if (jenis === "foto") {
                                                            tempatFoto.style.display = "block";
                                                            tempatVideo.style.display = "none";
                                                            inputFoto.setAttribute("required", "required");
                                                        } else if (jenis === "video") {
                                                            tempatFoto.style.display = "none";
                                                            tempatVideo.style.display = "block";
                                                            inputFoto.setAttribute("required", "required");
                                                        } else {
                                                            tempatFoto.style.display = "none";
                                                            tempatVideo.style.display = "none";
                                                        }
                                                    }
                                                </script>



                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keluar</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-tray-plus label-icon"></i>Add</button>
                                        </div>
                                    </form>
                                    <script>
                                        document.getElementById('formadd').addEventListener('submit', function(event) {
                                            if (!this.checkValidity()) {
                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                return false;
                                            }
                                            var submitButton = this.querySelector('button[name="sb"]');
                                            submitButton.disabled = true;
                                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                            return true; // Allow form submission
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Foto</th>
                                    <th class="align-middle text-center">Nama Kegiatan</th>
                                    <th class="align-middle text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $no=0;
                            @endphp
                                @foreach($galery as $data)
                                @php
                                    $no++;
                                @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>
                                        @if($data->jenis == 'foto')
                                            <div class="d-flex justify-content-center">
                                                <img id="previewImage" src="{{ asset($data->url) }}" class="img-thumbnail" alt="Preview Image" style="width: 300px; height: 180px; position: relative;">
                                            </div>
                                        @elseif($data->jenis == 'video')
                                            <div class="d-flex justify-content-center">

                                                <div style="width: 300px; height: 180px; position: relative;">
                                                    <div class="ratio ratio-16x9">
                                                        <iframe src="{{ $data->url }}" title="YouTube video" allowfullscreen=""></iframe>
                                                    </div>
                                                </div>
                                            </div>

                                        @endif

                                    </td>
                                    <td>
                                        <h4 class="font-size-14">{{ $data->name }}</h4>

                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Aksi <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop">
                                                <li><a class="dropdown-item drpdwn-wrg" href="#" data-bs-toggle="modal" data-bs-target="#edit-user{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Ubah</a></li>
                                                <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete-user{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Hapus</a></li>

                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL --}}
                                <div class="left-align truncate-text">
                                    {{-- Modal Edit User --}}
                                    <div class="modal fade" id="edit-user{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Ubah</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstgalery.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Galery</label><label style="color: darkred">*</label>
                                                                <input class="form-control" id="name" name="name" type="text" value="{{ $data->name }}" placeholder="Masukan Nama Galery.." required>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="jenis" class="form-label">Jenis</label><label style="color: darkred">*</label>
                                                                <select class="form-select" name="jenis" id="jenis{{ $data->id }}" onchange="toggleJenis({{ $data->id }})">
                                                                    <option value="" disabled selected>-- Pilih Jenis --</option>
                                                                    <option value="foto" {{($data->jenis == 'foto') ? 'selected' : ''}}>Foto</option>
                                                                    <option value="video" {{($data->jenis == 'video') ? 'selected' : ''}}>Video</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3" id="tempat-foto-{{ $data->id }}" style="display: {{ $data->jenis == 'foto' ? 'block' : 'none' }};">
                                                                <label class="form-label" for="foto">Foto</label><label style="color: darkred">*</label>
                                                                <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" id="foto{{ $data->id }}" name="foto">
                                                                <div class="d-flex justify-content-center mt-2">
                                                                    <img id="previewImage{{ $data->id }}" src="{{ asset($data->url) }}" class="img-thumbnail" alt="Preview Image" style="width: 300px; height: 180px;">
                                                                </div>
                                                            </div>

                                                            <div class="mb-3" id="tempat-video-{{ $data->id }}" style="display: {{ $data->jenis == 'video' ? 'block' : 'none' }};">
                                                                <label class="form-label">Url Video Youtube</label><label style="color: darkred">*</label>
                                                                <input class="form-control" id="video{{ $data->id }}" name="video" type="url" value="{{ $data->url }}" placeholder="Masukan Url Video Youtube..">
                                                            </div>

                                                            <script>
                                                                // Fungsi untuk menampilkan atau menyembunyikan input foto/video berdasarkan jenis yang dipilih
                                                                function toggleJenis(userId) {
                                                                    var jenis = document.getElementById("jenis" + userId).value;
                                                                    var tempatFoto = document.getElementById("tempat-foto-" + userId);
                                                                    var tempatVideo = document.getElementById("tempat-video-" + userId);
                                                                    var inputFoto = document.getElementById("foto" + userId);
                                                                    var inputVideo = document.getElementById("video" + userId);

                                                                    // Menyembunyikan dan menampilkan input sesuai dengan jenis yang dipilih
                                                                    if (jenis === "foto") {
                                                                        tempatFoto.style.display = "block";
                                                                        tempatVideo.style.display = "none";
                                                                        inputFoto.setAttribute("required", "required");  // Set foto sebagai required
                                                                        inputVideo.removeAttribute("required");  // Hapus required dari video
                                                                    } else if (jenis === "video") {
                                                                        tempatFoto.style.display = "none";
                                                                        tempatVideo.style.display = "block";
                                                                        inputVideo.setAttribute("required", "required");  // Set video sebagai required
                                                                        inputFoto.removeAttribute("required");  // Hapus required dari foto
                                                                    } else {
                                                                        tempatFoto.style.display = "none";
                                                                        tempatVideo.style.display = "none";
                                                                    }
                                                                }

                                                                // Menambahkan event listener untuk pratinjau foto
                                                                document.getElementById("foto{{ $data->id }}")?.addEventListener("change", function(event) {
                                                                    var input = event.target;
                                                                    var reader = new FileReader();
                                                                    reader.onload = function() {
                                                                        var imgElement = document.getElementById("previewImage{{ $data->id }}");
                                                                        imgElement.src = reader.result;
                                                                        imgElement.style.display = "block";  // Menampilkan pratinjau gambar
                                                                    };
                                                                    reader.readAsDataURL(input.files[0]);
                                                                });

                                                                // Memanggil toggleJenis saat halaman dimuat untuk menyesuaikan dengan pilihan yang sudah ada
                                                                toggleJenis({{ $data->id }});
                                                            </script>


                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keluar</button>
                                                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light" id="sb-edit{{ $data->id }}"><i class="mdi mdi-update label-icon"></i>Perbarui</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let userId = "{{ $data->id }}";
                                                        $('#formedit' + userId).submit(function(e) {
                                                            if (!$('#formedit' + userId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-edit' + userId).attr("disabled", "disabled");
                                                                $('#sb-edit' + userId).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Delete User --}}
                                    <div class="modal fade" id="delete-user{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstgalery.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p class="text-center">Anda Yakin Untuk Menghapus Data?</p>
                                                            <p class="text-center"><b>{{ $data->name }}</b></p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keluar</button>
                                                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-delete{{ $data->id }}"><i class="mdi mdi-delete label-icon"></i>Hapus</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let userId = "{{ $data->id }}";
                                                        $('#formdelete' + userId).submit(function(e) {
                                                            if (!$('#formdelete' + userId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-delete' + userId).attr("disabled", "disabled");
                                                                $('#sb-delete' + userId).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
