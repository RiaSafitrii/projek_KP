@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Konfigurasi</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('mstkelolanavigasi.index') }}">Group Navigasi</a></li>
                        <li class="breadcrumb-item active">
                            Daftar Navigasi Group : {{ $group->name }}
                        </li>
                    </ol>
                    <a id="backButton" type="button" href="{{ route('mstkelolanavigasi.index') }}"
                        class="btn btn-sm btn-secondary waves-effect btn-label waves-light">
                        <i class="mdi mdi-arrow-left-circle label-icon"></i>
                        Kembali
                    </a>

                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if(Auth::user()->role == 'admin')
                            <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-tray-plus label-icon"></i> Tambah</button>

                            {{-- Modal Tambah --}}
                            <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-top" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Tambah</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                        </div>
                                        <form action="{{ route('mstnavigasi.store', encrypt($group->id)) }}" id="formadd" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama</label><label style="color: darkred">*</label>
                                                            <input class="form-control" name="name" type="text" value="" placeholder="Masukkan Nama.." required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Code</label><label style="color: darkred">*</label>
                                                            <textarea class="form-control" rows="8" name="value" placeholder="Masukkan Value.." required></textarea>
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
                        @endif
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Nama</th>
                                    @if(Auth::user()->role == 'admin')
                                    <th class="align-middle text-center">Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                            @php $no = 0; @endphp
                            @foreach($navigation as $data)
                                @php $no++; @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $data->name }}</td>
                                    @if(Auth::user()->role == 'admin')
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Aksi <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop">
                                                <li><a class="dropdown-item drpdwn-wrg" href="#" data-bs-toggle="modal" data-bs-target="#edit-user{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
                                                <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete-user{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Hapus</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    @endif
                                </tr>

                                {{-- Modal Edit --}}
                                <div class="modal fade" id="edit-user{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Edit</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <form action="{{ route('mstnavigasi.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="name" type="text" value="{{ $data->name }}" placeholder="Masukkan Nama.." required>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label class="form-label">Code</label><label style="color: darkred">*</label>
                                                            <textarea class="form-control" rows="8" name="value" placeholder="Masukkan Value.." required>{{ $data->value }}</textarea>
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
                                                    let userId = "{{ $data->id }}";
                                                    $('#formedit' + userId).submit(function(e) {
                                                        if (!$('#formedit' + userId).valid()){
                                                            e.preventDefault();
                                                        } else {
                                                            $('#sb-edit' + userId).attr("disabled", "disabled");
                                                            $('#sb-edit' + userId).html('<i class="mdi mdi-reload label-icon"></i>Mohon Tunggu...');
                                                        }
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Hapus --}}
                                <div class="modal fade" id="delete-user{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-top" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                            </div>
                                            <form action="{{ route('mstnavigasi.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-body">
                                                    <p class="text-center">Apakah Anda yakin ingin menghapus navigasi ini?</p>
                                                    <p class="text-center"><b>{{ $data->name }}</b></p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
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
                                                            $('#sb-delete' + userId).html('<i class="mdi mdi-reload label-icon"></i>Mohon Tunggu...');
                                                        }
                                                    });
                                                });
                                            </script>
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
