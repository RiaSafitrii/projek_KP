@extends('layouts.master')

@section('konten')

<style>
    .table td:nth-child(3) {
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
                    <h4 class="mb-sm-0 font-size-18">Data Puskesmas</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Information</a></li>
                            <li class="breadcrumb-item active">Kelola Puskesmas</li>
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
                                    <form action="{{ route('mstdatapuskesmas.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="name" type="text" placeholder="Masukkan nama..." required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Alamat</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="alamat" type="text" placeholder="Masukkan Alamat..." required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Domain</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="domain" type="text" placeholder="Masukkan Domain..." required>
                                                    </div>
                                                </div>



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
                                    <th class="align-middle text-center">Nama</th>
                                    <th class="align-middle text-center">Alamat</th>
                                    <th class="align-middle text-center">Domain</th>
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
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Alamat</label><label style="color: darkred">*</label>
                                <input class="form-control" id="edit-alamat" name="alamat" type="text" placeholder="Masukkan Alamat..." required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Domain</label><label style="color: darkred">*</label>
                                <input class="form-control" id="edit-domain" name="domain" type="text" placeholder="Masukkan Domain..." required>
                            </div>
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





</div>



<script>
    $(function() {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('mstdatapuskesmas.index') }}',
            columns: [
                { data: null, name: 'no', orderable: false, searchable: false }, // No urut manual
                { data: 'name', name: 'puskesmas.name' },
                { data: 'alamat', name: 'puskesmas.alamat' },
                { data: 'domain', name: 'puskesmas.domain' },
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
            const alamat = $(this).data('alamat');
            const domain = $(this).data('domain');

            $('#form-edit').attr('action', route);
            $('#edit-name').val(title);
            $('#edit-alamat').val(alamat);
            $('#edit-domain').val(domain);

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







</script>




@endsection
