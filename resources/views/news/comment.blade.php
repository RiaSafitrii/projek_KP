@extends('layouts.master')

@section('konten')

<style>
    .table td:nth-child(2) {
        max-width: 150px !important;
        word-wrap: break-word !important;
        white-space: normal !important;
        overflow: visible !important;
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Daftar Komentar</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Post</a></li>
                            <li class="breadcrumb-item active">Kelola Komentar</li>
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

                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Nama</th>
                                    <th class="align-middle text-center">Komentar</th>
                                    <th class="align-middle text-center">Status</th>
                                    <th class="align-middle text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $no=0;
                            @endphp
                                @foreach($comment as $data)
                                @php
                                    $no++;
                                @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->comment }}</td>
                                    <td>
                                        <small class="text-muted">
                                            <span class="badge {{ $data->is_approved ? 'bg-success' : 'bg-danger' }} text-white">
                                                {{ $data->is_approved ? 'Disetujui' : 'Belum Disetujui' }}
                                            </span>

                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Aksi <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop">
                                                <li><a class="dropdown-item drpdwn-wrg" href="{{ route('mstnews.detail', encrypt($data->news_id)) }}"><span class="mdi mdi-newspaper"></span> | Detail Berita</a></li>
                                                <li><a class="dropdown-item drpdwn-wrg" href="#" data-bs-toggle="modal" data-bs-target="#edit-news{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Status</a></li>
                                                <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete-news{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL --}}
                                <div class="left-align truncate-text">
                                    {{-- Modal Edit Comments --}}
                                    <div class="modal fade" id="edit-news{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Status Komentar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstnews.status_comment', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p class="text-center">Apakah Anda Yakin untuk {{ $data->is_approved ? 'Tidak Menyetujui' : 'Menyetujui' }} Komentar ini?</p>
                                                            <p class="text-center">Nama: <b>{{ $data->name }}</b></p>
                                                            <p class="text-center">Komentar: <b>{{ $data->comment }}</b></p>
                                                            <p class="text-center">Berita: <b>{{ $data->title }}</b></p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn {{ $data->is_approved ? 'btn-warning' : 'btn-success' }} waves-effect waves-light">
                                                            {{ $data->is_approved ? 'Tidak Menyetujui' : 'Menyetujui' }}
                                                        </button>
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

                                    {{-- Modal Delete Comments --}}
                                    <div class="modal fade" id="delete-news{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Hapus Komentar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstnews.delete_comment', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p class="text-center">Apakah Anda Yakin untuk Menghapus Komentar ini?</p>
                                                            <p class="text-center">Nama: <b>{{ $data->name }}</b></p>
                                                            <p class="text-center">Komentar: <b>{{ $data->comment }}</b></p>
                                                            <p class="text-center">Berita: <b>{{ $data->title }}</b></p>
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
