@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Daftar Chat Admin</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Konfigurasi</a></li>
                            <li class="breadcrumb-item active">Kelola Chat Admin</li>
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
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-tray-plus label-icon"></i> Tambah Baru</button>

                        {{-- Modal Tambah --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Tambah Baru</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>
                                    <form action="{{ route('mstchatadmin.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Info</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="info_name" type="text" placeholder="Masukkan nama info.." required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nomor WA</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="info_value" type="text" onkeypress="return hanyaAngka(event)" placeholder="Masukkan nomor.." required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Pesan WA</label><label style="color: darkred">*</label>
                                                        <textarea class="form-control" name="info_name_url" placeholder="Masukkan pesan.." required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-tray-plus label-icon"></i>Tambah</button>
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
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Nama</th>
                                    <th class="align-middle text-center">Nomor</th>
                                    <th class="align-middle text-center">Pesan</th>
                                    <th class="align-middle text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $no = 0;
                            @endphp
                            @foreach($chatadmin as $data)
                                @php
                                    $no++;
                                @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $data->info_name }}</td>
                                    <td>{{ $data->info_value }}</td>
                                    <td>{{ $data->info_name_url }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Aksi <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop">
                                                <li><a class="dropdown-item drpdwn-wrg" href="#" data-bs-toggle="modal" data-bs-target="#edit-chatadmin{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Ubah</a></li>
                                                <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete-chatadmin{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL --}}
                                <div class="left-align truncate-text">
                                    {{-- Modal Edit --}}
                                    <div class="modal fade" id="edit-chatadmin{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Ubah</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <form action="{{ route('mstchatadmin.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama Info</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="info_name" value="{{ $data->info_name }}" type="text" placeholder="Masukkan nama info.." required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nomor WA</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="info_value" type="text" value="{{ $data->info_value }}" onkeypress="return hanyaAngka(event)" placeholder="Masukkan nomor.." required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Pesan WA</label><label style="color: darkred">*</label>
                                                                    <textarea class="form-control" name="info_name_url" placeholder="Masukkan pesan.." required>{{ $data->info_name_url }}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light" id="sb-edit{{ $data->id }}"><i class="mdi mdi-update label-icon"></i>Perbarui</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let panduanId = "{{ $data->id }}";
                                                        $('#formedit' + panduanId).submit(function(e) {
                                                            if (!$('#formedit' + panduanId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-edit' + panduanId).attr("disabled", "disabled");
                                                                $('#sb-edit' + panduanId).html('<i class="mdi mdi-reload label-icon"></i>Mohon Tunggu...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Hapus --}}
                                    <div class="modal fade" id="delete-chatadmin{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <form action="{{ route('mstchatadmin.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p class="text-center">Apakah Anda yakin ingin menghapus ini?</p>
                                                            <p class="text-center"><b>{{ $data->info_name }}</b></p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-delete{{ $data->id }}"><i class="mdi mdi-delete label-icon"></i>Hapus</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let panduanId = "{{ $data->id }}";
                                                        $('#formdelete' + panduanId).submit(function(e) {
                                                            if (!$('#formdelete' + panduanId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-delete' + panduanId).attr("disabled", "disabled");
                                                                $('#sb-delete' + panduanId).html('<i class="mdi mdi-reload label-icon"></i>Mohon Tunggu...');
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
