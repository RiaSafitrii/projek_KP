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
                            @php
                                $no=0;
                            @endphp
                                @foreach($news as $data)
                                @php
                                    $no++;
                                @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $data->title }}</td>
                                    <td>{{ $data->name_category }}</td>
                                    <td>{{ Carbon\Carbon::parse($data->publish_date)->translatedFormat('d F Y H:i') }}</td>
                                    <td>{{ $data->operator }}</td>
                                    <td>{{ $data->penulis }}</td>
                                    <td>{{ $data->comments_count }}</td>
                                    <td class="text-center">
                                        @if($data->status == 'actived')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(Auth::user()->id === $data->operator_id || Auth::user()->role === 'admin')
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Aksi <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop">
                                                <li><a class="dropdown-item drpdwn-wrg" href="{{ route('mstnews.detail', encrypt($data->id)) }}"><span class="mdi mdi-newspaper"></span> | Detail</a></li>
                                                <li><a class="dropdown-item drpdwn-wrg" href="#" data-bs-toggle="modal" data-bs-target="#edit-news{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
                                                @if($data->status == 'actived')
                                                    <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | Nonaktifkan</a></li>
                                                @else
                                                    <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | Aktifkan</a></li>
                                                @endif
                                                <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete-news{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Hapus</a></li>
                                            </ul>
                                        </div>
                                        @else

                                        <span class="badge bg-info">Ini bukan konten Anda</span>

                                        @endif
                                    </td>
                                </tr>

                                {{-- MODAL --}}
                                <div class="left-align truncate-text">
                                    {{-- Modal Edit News --}}
                                    <div class="modal fade" id="edit-news{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Edit Berita</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstnews.edit', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="GET" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p class="text-center">Apakah Anda Yakin untuk Mengedit Berita ini?</p>
                                                            <p class="text-center"><b>{{ $data->title }}</b></p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-warning waves-effect btn-label waves-light" id="sb-edit{{ $data->id }}"><i class="mdi mdi-file-edit label-icon"></i>Edit</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let newsId = "{{ $data->id }}";
                                                        $('#formedit' + newsId).submit(function(e) {
                                                            if (!$('#formedit' + newsId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-edit' + newsId).attr("disabled", "disabled");
                                                                $('#sb-edit' + newsId).html('<i class="mdi mdi-reload label-icon"></i>Mohon Tunggu...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Delete News --}}
                                    <div class="modal fade" id="delete-news{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Hapus Berita</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstnews.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p class="text-center">Apakah Anda Yakin untuk Menghapus Berita ini?</p>
                                                            <p class="text-center"><b>{{ $data->title }}</b></p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-delete{{ $data->id }}"><i class="mdi mdi-delete label-icon"></i>Hapus</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let newsId = "{{ $data->id }}";
                                                        $('#formdelete' + newsId).submit(function(e) {
                                                            if (!$('#formdelete' + newsId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-delete' + newsId).attr("disabled", "disabled");
                                                                $('#sb-delete' + newsId).html('<i class="mdi mdi-reload label-icon"></i>Mohon Tunggu...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Modal Activate --}}
                                    <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Menayangkan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstnews.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            Apakah Anda yakin ingin <b>Menayangkan</b> Berita ini?
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="sb-activate{{ $data->id }}"><i class="mdi mdi-check-circle label-icon"></i>Activate</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let idList = "{{ $data->id }}";
                                                        $('#formactivate' + idList).submit(function(e) {
                                                            if (!$('#formactivate' + idList).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-activate' + idList).attr("disabled", "disabled");
                                                                $('#sb-activate' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                     {{-- Modal Deactivate --}}
                                    <div class="modal fade" id="deactivate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Menonaktifkan</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstnews.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            Apakah Anda yakin ingin <b>Menonaktifkan</b> Berita ini?
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deactivate{{ $data->id }}"><i class="mdi mdi-close-circle label-icon"></i>Deactivate</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let idList = "{{ $data->id }}";
                                                        $('#formdeactivate' + idList).submit(function(e) {
                                                            if (!$('#formdeactivate' + idList).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-deactivate' + idList).attr("disabled", "disabled");
                                                                $('#sb-deactivate' + idList).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
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
