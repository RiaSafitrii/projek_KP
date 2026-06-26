@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <!-- Judul Halaman -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Daftar Pengguna</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                            <li class="breadcrumb-item active">Kelola Pengguna</li>
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
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new">
                            <i class="mdi mdi-account-plus label-icon"></i> Tambah Pengguna Baru
                        </button>

                        {{-- Modal Tambah --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah Pengguna</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('user.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
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
                                                        <label class="form-label">Email</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="email" type="email" placeholder="Masukkan email..." required>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Kata Sandi</label><label style="color: darkred">*</label>
                                                    <div class="input-group auth-pass-inputgroup">
                                                        <input type="password" class="form-control" name="password" placeholder="Masukkan kata sandi" aria-describedby="password-addon" autocomplete="new-password" required
                                                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                                        title="Kata sandi harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter khusus.">
                                                        <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon"><i class="mdi mdi-eye-outline"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                            <button type="submit" class="btn btn-success" id="button_id"><i class="mdi mdi-account-plus label-icon"></i> Tambah</button>
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
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Peran</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($user as $data)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ ucwords($data->role) }}</td>
                                    <td class="text-center">
                                        @if($data->status == 'actived')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                Aksi <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item drpdwn-wrg" href="#" data-bs-toggle="modal" data-bs-target="#edit-user{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Ubah</a></li>

                                                @if($data->status == 'actived')
                                                    <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#deactivate{{ $data->id }}"><span class="mdi mdi-close-circle"></span> | Nonaktifkan</a></li>
                                                @else
                                                    <li><a class="dropdown-item drpdwn-scs" href="#" data-bs-toggle="modal" data-bs-target="#activate{{ $data->id }}"><span class="mdi mdi-check-circle"></span> | Aktifkan</a></li>
                                                @endif
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
                                                    <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('user.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nama</label><label style="color: darkred">*</label>
                                                                    <div id="namaWarning"></div>
                                                                    <input class="form-control" id="cek_nama" name="name" type="text" value="{{ $data->name }}" placeholder="Masukan Nama.." required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Email</label><label style="color: darkred">*</label>
                                                                    <div id="emailWarning"></div>
                                                                    <input class="form-control" id="cek_mail" name="email" type="email" value="{{ $data->email }}" placeholder="Masukan Email.." required>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="flex-grow-1">
                                                                        <label class="form-label">Kata Sandi</label>
                                                                    </div>
                                                                </div>

                                                                <div class="input-group auth-pass-inputgroup">
                                                                    <input type="password" autocomplete="new-password" class="form-control" placeholder="Masukan Kata Sandi" aria-label="Password" aria-describedby="password-addon-{{ $data->id }}" name="password" id="password-{{ $data->id }}"
                                                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$"
                                                                    title="Kata sandi harus minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan karakter khusus.">
                                                                    <button class="btn btn-light shadow-none ms-0" type="button" id="password-addon-{{ $data->id }}"><i class="mdi mdi-eye-outline"></i></button>
                                                                </div>
                                                            </div>
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
                                                    <h5 class="modal-title" id="staticBackdropLabel">Hapus User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('user.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p class="text-center">Anda Yakin Menghapus Pengguna?</p>
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

                                    {{-- Modal Activate --}}
                                    <div class="modal fade" id="activate{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Mengaktifkan Pengguna</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('user.activate', encrypt($data->id)) }}" id="formactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            Apakah Anda yakin ingin <b>Mengaktifkan</b> pengguna ini?
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keluar</button>
                                                        <button type="submit" class="btn btn-success waves-effect btn-label waves-light" id="sb-activate{{ $data->id }}"><i class="mdi mdi-check-circle label-icon"></i>Aktifkan</button>
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
                                                    <h5 class="modal-title" id="staticBackdropLabel">Menonaktifkan Pengguna</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('user.deactivate', encrypt($data->id)) }}" id="formdeactivate{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="text-center">
                                                            Apakah Anda yakin ingin <b>Menonaktifkan</b> pengguna ini?
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Keluar</button>
                                                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-deactivate{{ $data->id }}"><i class="mdi mdi-close-circle label-icon"></i>Matikan</button>
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

{{-- Script Toggle Password --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggles = document.querySelectorAll('[id^="password-addon-"]');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function () {
                const id = toggle.id.split('password-addon-')[1];
                const input = document.getElementById(`password-${id}`);
                if (input.type === 'password') {
                    input.type = 'text';
                    toggle.innerHTML = '<i class="mdi mdi-eye-off-outline"></i>';
                } else {
                    input.type = 'password';
                    toggle.innerHTML = '<i class="mdi mdi-eye-outline"></i>';
                }
            });
        });
    });
</script>

@endsection
