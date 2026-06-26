@extends('layouts.master')

@section('konten')

<style>
    .table td:nth-child(2) {
        max-width: 150px !important; /* Batas lebar kolom */
        word-wrap: break-word !important; /* Memecah kata agar tidak meluap */
        white-space: normal !important;   /* Membungkus teks ke baris berikutnya */
        overflow: visible !important;    /* Pastikan konten yang dibungkus tidak tersembunyi */
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Data Slider</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Informasi</a></li>
                            <li class="breadcrumb-item active">Kelola Slider</li>
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
                        <button class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-tray-plus label-icon"></i> Tambah Baru</button>
                        {{-- Modal Tambah --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('mstslider.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="name" type="text" placeholder="Masukkan nama..." required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="thumbnail">Slider</label><label style="color: darkred">(Resolusi Disarankan 1080x540px)*</label>
                                                    <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" id="thumbnail" name="thumbnail">
                                                    <img id="previewImage" src="#" class="img-thumbnail mt-2" alt="Preview Image" width="400" style="display: none;">
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

                                                </script>


                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-success" id="button_id"><i class="mdi mdi-tray-plus label-icon"></i> Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Slider</th>
                                    <th class="align-middle text-center">Nama</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


{{-- Modal --}}
<div class="left-align truncate-text">
    <!-- Modal Edit  -->
    <div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Nama</label><label style="color: darkred">*</label>
                                <input class="form-control" id="edit-name" name="name" type="text" placeholder="Masukkan nama..." required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="thumbnail">Slider</label><label style="color: darkred">(Resolusi Disarankan 1080x540px)*</label>
                            <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="form-control" id="thumbnail" name="thumbnail">
                            <img class="img-thumbnail mt-2 preview-image" alt="Preview Image" width="400" style="display: none;">
                        </div>


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-warning waves-effect btn-label waves-light" id="btn-submit-edit"><i class="mdi mdi-file-edit label-icon"></i>Perbarui</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteLabel">Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-delete" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin menghapus Data ini?</p>
                    <p><b id="delete-title-text"></b></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="btn-submit-delete"><i class="mdi mdi-delete label-icon"></i>Hapus</button>
                </div>
            </form>
            </div>
        </div>
    </div>


     <!-- Modal Activate -->
    <div class="modal fade" id="modal-activate" tabindex="-1" aria-labelledby="modalActivateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActivateLabel">Aktifkan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-activate" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin <b>Menayangkan</b> slider ini?</p>
                    <p><b id="activate-title-text"></b></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="btn-submit-activate">
                        <i class="mdi mdi-check-circle label-icon"></i> Aktifkan
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Deactivate -->
    <div class="modal fade" id="modal-deactivate" tabindex="-1" aria-labelledby="modalDeactivateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeactivateLabel">Menonaktifkan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-deactivate" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin <b>Menonaktifkan</b> ini?</p>
                    <p><b id="deactivate-title-text"></b></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="btn-submit-deactivate">
                        <i class="mdi mdi-close-circle label-icon"></i>Nonaktifkan
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>




</div>



<script>
    $(function() {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('mstslider.index') }}',
            columns: [
                { data: null, name: 'no', orderable: false, searchable: false }, // No urut manual
                { data: 'gambar', name: 'gambar', orderable: false, searchable: false },
                { data: 'name', name: 'slider.name' },
                { data: 'status', name: 'slider.status' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[1, 'asc']], // default sorting by title maybe
            columnDefs: [{
                targets: 0,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }]
        });

    });
</script>

<script>
    $(document).ready(function() {
        $('#datatable').on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            const route = $(this).data('route');
            const status = $(this).data('status');

            $('#form-edit').attr('action', route);
            $('#edit-name').val(title);
            $('#edit-status').val(status);

            // Optional: tombol loading saat submit
            $('#form-edit').off('submit').on('submit', function(e) {
                $('#btn-submit-edit').attr('disabled', true).html('<i class="mdi mdi-reload label-icon"></i> Mohon Tunggu...');
            });
        });
    });



    $(document).ready(function() {
        $('#datatable').on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            const route = $(this).data('route');

            $('#delete-title-text').text(title);
            $('#form-delete').attr('action', route);

            $('#form-delete').off('submit').on('submit', function(e) {
                $('#btn-submit-delete').attr('disabled', true).html('<i class="mdi mdi-reload label-icon"></i>Mohon Tunggu...');
            });
        });
    });



    $(document).ready(function() {
        $('#datatable').on('click', '.btn-activate', function() {
            const title = $(this).data('title');
            const route = $(this).data('route');

            $('#activate-title-text').text(title);
            $('#form-activate').attr('action', route);

            $('#form-activate').off('submit').on('submit', function(e) {
                $('#btn-submit-activate').attr('disabled', true).html('<i class="mdi mdi-reload label-icon"></i> Mohon Tunggu...');
            });
        });
    });

    $(document).ready(function() {
        $('#datatable').on('click', '.btn-deactivate', function() {
            const title = $(this).data('title');
            const route = $(this).data('route');

            $('#deactivate-title-text').text(title);
            $('#form-deactivate').attr('action', route);

            $('#form-deactivate').off('submit').on('submit', function(e) {
                $('#btn-submit-deactivate').attr('disabled', true).html('<i class="mdi mdi-reload label-icon"></i> Mohon Tunggu...');
            });
        });
    });




</script>




@endsection
