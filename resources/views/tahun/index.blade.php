@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <!-- Judul Halaman -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Data Tahun</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Konfigurasi</a></li>
                            <li class="breadcrumb-item active">Kelola Tahun</li>
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
                            <i class="mdi mdi-tray-plus label-icon"></i> Tambah
                        </button>

                        {{-- Modal Tambah --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tambah</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('msttahun.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tahun</label><label style="color: darkred">*</label>
                                                        <input class="form-control input-year" name="name" type="text" placeholder="Masukkan Tahun..." maxlength="4" onkeypress="return hanyaAngka(event)" required>
                                                        <div class="invalid-feedback year-error" style="display: none;">Tahun harus berupa 4 digit angka minimal 2012.</div>
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
                                    <th class="text-center">No</th>
                                    <th class="text-center">Tahun</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($tahun as $data)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $data->year }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                Aksi <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item drpdwn-wrg" href="#" data-bs-toggle="modal" data-bs-target="#edit{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Ubah</a></li>
                                                <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Delete</a></li>

                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                 {{-- MODAL --}}
                                <div class="left-align truncate-text">
                                    {{-- Modal Edit User --}}
                                    <div class="modal fade" id="edit{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('msttahun.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tahun</label><label style="color: darkred">*</label>
                                                                    <input class="form-control input-year" name="name" type="text" value="{{ $data->year }}" placeholder="Masukkan Tahun..." maxlength="4" onkeypress="return hanyaAngka(event)" required>
                                                                    <div class="invalid-feedback year-error" style="display: none;">Tahun harus berupa 4 digit angka minimal 2012.</div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light" id="sb-edit{{ $data->id }}"><i class="mdi mdi-update label-icon"></i>Update</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let tahunId = "{{ $data->id }}";
                                                        $('#formedit' + tahunId).submit(function(e) {
                                                            if (!$('#formedit' + tahunId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-edit' + tahunId).attr("disabled", "disabled");
                                                                $('#sb-edit' + tahunId).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Delete --}}
                                    <div class="modal fade" id="delete{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Delete User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('msttahun.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p class="text-center">Anda Yakin Ingin Menghapus Tahun?</p>
                                                            <p class="text-center"><b>{{ $data->year }}</b></p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-delete{{ $data->id }}"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let tahunId = "{{ $data->id }}";
                                                        $('#formdelete' + tahunId).submit(function(e) {
                                                            if (!$('#formdelete' + tahunId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-delete' + tahunId).attr("disabled", "disabled");
                                                                $('#sb-delete' + tahunId).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
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


<script>
    function validateYearInput(inputYear, errorMsg) {
        const value = inputYear.value.trim();
        const isValid = /^[0-9]{4}$/.test(value) && parseInt(value) >= 2012;

        if (!isValid) {
            inputYear.classList.add('is-invalid');
            errorMsg.style.display = 'block';
        } else {
            inputYear.classList.remove('is-invalid');
            errorMsg.style.display = 'none';
        }

        return isValid;
    }

    // Saat modal ditampilkan, pasang event listener
    document.addEventListener('shown.bs.modal', function (event) {
        const modal = event.target;
        const form = modal.querySelector('form');
        const inputYear = modal.querySelector('.input-year');
        const errorMsg = modal.querySelector('.year-error');

        if (!form || !inputYear || !errorMsg) return;

        inputYear.addEventListener('input', () => validateYearInput(inputYear, errorMsg));

        form.addEventListener('submit', function (e) {
            const valid = validateYearInput(inputYear, errorMsg);
            if (!valid) {
                e.preventDefault();
            }
        });
    });
</script>





@endsection
