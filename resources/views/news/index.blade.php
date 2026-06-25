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
                    <h4 class="mb-sm-0 font-size-18">Daftar Konten</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Post</a></li>
                            <li class="breadcrumb-item active">Kelola Konten</li>
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
                        <a href="{{ route('mstnews.add') }}" type="button" class="btn btn-primary waves-effect btn-label waves-light"><i class="mdi mdi-tray-plus label-icon"></i> Tambah Berita Baru</a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Nama</th>
                                    <th class="align-middle text-center">Kategori</th>
                                    <th class="align-middle text-center">Tanggal Terbit</th>
                                    <th class="align-middle text-center">Operator</th>
                                    <th class="align-middle text-center">Penulis</th>
                                    <th class="align-middle text-center">Komentar</th>
                                    <th class="text-center">Status</th>
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
    <!-- Modal Edit News -->
    <div class="modal fade" id="modal-edit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditLabel">Edit Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-edit" method="GET" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-center">
                    <p>Apakah Anda Yakin untuk Mengedit Berita ini?</p>
                    <p><b id="edit-title-text"></b></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-warning waves-effect btn-label waves-light" id="btn-submit-edit"><i class="mdi mdi-file-edit label-icon"></i>Edit</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete News -->
    <div class="modal fade" id="modal-delete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteLabel">Hapus Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-delete" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin menghapus berita ini?</p>
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


    <!-- Modal Activate News -->
    <div class="modal fade" id="modal-activate" tabindex="-1" aria-labelledby="modalActivateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActivateLabel">Aktifkan Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-activate" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin <b>Menayangkan</b> berita ini?</p>
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

    <!-- Modal Deactivate News -->
    <div class="modal fade" id="modal-deactivate" tabindex="-1" aria-labelledby="modalDeactivateLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDeactivateLabel">Menonaktifkan Berita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-deactivate" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body text-center">
                    <p>Apakah Anda yakin ingin <b>Menonaktifkan</b> berita ini?</p>
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
            ajax: '{{ route('mstnews.index') }}',
            columns: [
                { data: null, name: 'no', orderable: false, searchable: false }, // No urut manual
                { data: 'title', name: 'news.title' },
                { data: 'name_category', name: 'categories.name' },
                { data: 'formatted_publish_date', name: 'news.publish_date' },
                { data: 'operator', name: 'news.operator' },
                { data: 'penulis', name: 'author.name' },
                { data: 'comments_count', name: 'comments_count' },
                { data: 'status', name: 'news.status' },
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

            $('#edit-title-text').text(title);
            $('#form-edit').attr('action', route);

            // Optional: kalau mau ganti tombol submit saat submit form
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
